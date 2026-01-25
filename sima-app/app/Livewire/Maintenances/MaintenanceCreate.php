<?php

namespace App\Livewire\Maintenances;

use Livewire\Component;
use App\Models\Asset;
use App\Models\Maintenance;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MaintenanceCreate extends Component
{
    public $asset_id = '';
    public $type = 'repair';
    public $priority = 'medium';
    public $title = '';
    public $description = '';
    public $scheduled_date = '';
    public $estimated_cost = 0;
    public $assigned_to = '';
    public $vendor_name = '';
    
    // For displaying asset info
    public $selectedAssetInfo = null;
    
    protected $rules = [
        'asset_id' => 'required|exists:assets,id',
        'type' => 'required|in:scheduled,repair,inspection',
        'priority' => 'required|in:low,medium,high,critical',
        'title' => 'required|string|max:255',
        'description' => 'required|string',
        'scheduled_date' => 'nullable|date',
        'estimated_cost' => 'nullable|numeric|min:0',
        'assigned_to' => 'nullable|exists:users,id',
        'vendor_name' => 'nullable|string|max:255',
    ];
    
    public function mount()
    {
        $this->scheduled_date = now()->format('Y-m-d');
    }
    
    public function updatedAssetId()
    {
        $this->selectedAssetInfo = null;
        
        if ($this->asset_id) {
            $asset = Asset::with(['assignedUser', 'location', 'maintenances' => function($q) {
                $q->whereIn('status', ['pending', 'in_progress']);
            }])->find($this->asset_id);
            
            if ($asset) {
                $this->selectedAssetInfo = [
                    'code' => $asset->code,
                    'name' => $asset->name,
                    'status' => $asset->status,
                    'condition' => $asset->condition,
                    'assigned_to' => $asset->assignedUser?->name,
                    'location' => $asset->location?->name,
                    'has_active_maintenance' => $asset->maintenances->isNotEmpty(),
                    'active_maintenance_count' => $asset->maintenances->count(),
                ];
            }
        }
    }
    
    public function save()
    {
        $this->validate();
        
        $asset = Asset::find($this->asset_id);
        
        if (!$asset) {
            $this->addError('asset_id', 'Aset tidak ditemukan.');
            return;
        }
        
        // === VALIDATION ===
        
        // Check if asset is disposed/hilang
        if ($asset->status === Asset::STATUS_DISPOSAL) {
            $this->addError('asset_id', 'Aset sudah dihapuskan (disposal). Tidak dapat membuat tiket pemeliharaan.');
            return;
        }
        
        if ($asset->condition === Asset::CONDITION_HILANG) {
            $this->addError('asset_id', 'Aset tercatat hilang. Tidak dapat membuat tiket pemeliharaan.');
            return;
        }
        
        // Check for existing active maintenance of same type
        $existingMaintenance = $asset->maintenances()
            ->whereIn('status', ['pending', 'in_progress'])
            ->where('type', $this->type)
            ->first();
        
        if ($existingMaintenance) {
            $typeLabel = match($this->type) {
                'scheduled' => 'terjadwal',
                'repair' => 'perbaikan',
                'inspection' => 'inspeksi',
                default => $this->type
            };
            $this->addError('asset_id', "Aset sudah memiliki tiket pemeliharaan {$typeLabel} yang aktif (#{$existingMaintenance->ticket_number}).");
            return;
        }
        
        // Warning for critical priority if there's any active maintenance
        if ($this->priority !== 'critical' && $asset->hasActiveMaintenance()) {
            // Allow but show message
        }
        
        Maintenance::create([
            'asset_id' => $this->asset_id,
            'type' => $this->type,
            'priority' => $this->priority,
            'title' => $this->title,
            'description' => $this->description,
            'scheduled_date' => $this->scheduled_date ?: null,
            'estimated_cost' => $this->estimated_cost ?: 0,
            'assigned_to' => $this->assigned_to ?: null,
            'vendor_name' => $this->vendor_name,
            'status' => 'pending',
            'reported_by' => Auth::id(),
        ]);
        
        session()->flash('message', 'Tiket pemeliharaan berhasil dibuat!');
        
        return $this->redirect(route('maintenances.index'), navigate: true);
    }
    
    public function render()
    {
        // Only show assets that can be maintained (not disposed or lost)
        $assets = Asset::where('status', '!=', Asset::STATUS_DISPOSAL)
            ->where('condition', '!=', Asset::CONDITION_HILANG)
            ->orderBy('code')
            ->get();
            
        $technicians = User::role(['Admin Aset', 'Staff'])->orderBy('name')->get();
        
        return view('livewire.maintenances.maintenance-create', [
            'assets' => $assets,
            'technicians' => $technicians,
        ])->layout('layouts.app');
    }
}
