<?php

namespace App\Livewire\Movements;

use Livewire\Component;
use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MovementCreate extends Component
{
    public $type = 'peminjaman';
    public $asset_id = '';
    public $from_user_id = '';
    public $to_user_id = '';
    public $from_location_id = '';
    public $to_location_id = '';
    public $movement_date = '';
    public $expected_return_date = '';
    public $reason = '';
    public $notes = '';
    
    // For displaying asset info
    public $selectedAssetInfo = null;
    
    protected function rules()
    {
        $rules = [
            'type' => 'required|in:peminjaman,mutasi,pengembalian',
            'asset_id' => 'required|exists:assets,id',
            'movement_date' => 'required|date',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string',
        ];
        
        if ($this->type === 'peminjaman') {
            $rules['to_user_id'] = 'required|exists:users,id';
            $rules['expected_return_date'] = 'required|date|after:movement_date';
        }
        
        if ($this->type === 'mutasi') {
            $rules['to_location_id'] = 'required|exists:locations,id';
        }
        
        return $rules;
    }
    
    public function mount()
    {
        $this->movement_date = now()->format('Y-m-d');
    }
    
    public function updatedAssetId()
    {
        $this->selectedAssetInfo = null;
        
        if ($this->asset_id) {
            $asset = Asset::with(['assignedUser', 'location'])->find($this->asset_id);
            if ($asset) {
                $this->from_user_id = $asset->assigned_to ?? '';
                $this->from_location_id = $asset->location_id ?? '';
                
                // Store asset info for display
                $this->selectedAssetInfo = [
                    'code' => $asset->code,
                    'name' => $asset->name,
                    'status' => $asset->status,
                    'condition' => $asset->condition,
                    'assigned_to' => $asset->assignedUser?->name,
                    'location' => $asset->location?->name,
                    'is_available' => $asset->isAvailable(),
                    'unavailability_reason' => $asset->getUnavailabilityReason(),
                ];
            }
        }
    }
    
    public function updatedType()
    {
        // Re-check asset when type changes
        if ($this->asset_id) {
            $this->updatedAssetId();
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
        
        // === VALIDATION BASED ON MOVEMENT TYPE ===
        
        if ($this->type === 'peminjaman') {
            // Check if asset is available for borrowing
            if (!$asset->isAvailable()) {
                $reason = $asset->getUnavailabilityReason() ?? 'Aset tidak tersedia';
                $this->addError('asset_id', $reason);
                return;
            }
            
            // Check for active maintenance
            if ($asset->hasActiveMaintenance()) {
                $this->addError('asset_id', 'Aset memiliki tiket pemeliharaan aktif. Selesaikan pemeliharaan terlebih dahulu.');
                return;
            }
            
            // Check for pending movement
            if ($asset->hasActiveMovement()) {
                $this->addError('asset_id', 'Aset memiliki transaksi sirkulasi yang belum selesai.');
                return;
            }
            
            // Cannot borrow to the same user
            if ($asset->assigned_to && $asset->assigned_to == $this->to_user_id) {
                $this->addError('to_user_id', 'Aset sudah ditugaskan ke user ini.');
                return;
            }
        }
        
        if ($this->type === 'mutasi') {
            // Check if asset can be moved (not in maintenance, not lost)
            if ($asset->isUnderMaintenance()) {
                $this->addError('asset_id', 'Aset sedang dalam pemeliharaan. Selesaikan pemeliharaan terlebih dahulu.');
                return;
            }
            
            if ($asset->condition === Asset::CONDITION_HILANG) {
                $this->addError('asset_id', 'Aset tercatat hilang. Tidak dapat dipindahkan.');
                return;
            }
            
            if ($asset->hasActiveMaintenance()) {
                $this->addError('asset_id', 'Aset memiliki tiket pemeliharaan aktif.');
                return;
            }
            
            // Cannot move to same location
            if ($asset->location_id && $asset->location_id == $this->to_location_id) {
                $this->addError('to_location_id', 'Aset sudah berada di lokasi ini.');
                return;
            }
        }
        
        if ($this->type === 'pengembalian') {
            // Must be currently in use
            if (!$asset->isInUse()) {
                $this->addError('asset_id', 'Aset tidak sedang dipinjam/digunakan.');
                return;
            }
        }
        
        // Generate BAST number
        $year = date('Y');
        $month = date('m');
        $lastBast = AssetMovement::whereYear('created_at', $year)
            ->whereMonth('created_at', $month)
            ->latest('id')
            ->first();
        $bastNumber = $lastBast ? intval(substr($lastBast->bast_number ?? '0000', -4)) + 1 : 1;
        $bastNumber = 'BAST-' . $year . $month . '-' . str_pad($bastNumber, 4, '0', STR_PAD_LEFT);
        
        AssetMovement::create([
            'asset_id' => $this->asset_id,
            'type' => $this->type,
            'from_user_id' => $this->from_user_id ?: null,
            'to_user_id' => $this->type === 'peminjaman' ? $this->to_user_id : null,
            'from_location_id' => $this->from_location_id ?: null,
            'to_location_id' => $this->type === 'mutasi' ? $this->to_location_id : ($this->from_location_id ?: null),
            'movement_date' => $this->movement_date,
            'expected_return_date' => $this->type === 'peminjaman' ? $this->expected_return_date : null,
            'reason' => $this->reason,
            'notes' => $this->notes,
            'bast_number' => $bastNumber,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);
        
        session()->flash('message', 'Transaksi sirkulasi berhasil dibuat dan menunggu persetujuan.');
        
        return $this->redirect(route('movements.index'), navigate: true);
    }
    
    public function render()
    {
        // Get assets based on movement type
        if ($this->type === 'peminjaman') {
            // Only show available assets
            $assets = Asset::where('status', Asset::STATUS_TERSEDIA)
                ->where('condition', '!=', Asset::CONDITION_HILANG)
                ->orderBy('code')
                ->get();
        } elseif ($this->type === 'pengembalian') {
            // Only show assets in use
            $assets = Asset::where('status', Asset::STATUS_DIGUNAKAN)
                ->orderBy('code')
                ->get();
        } else {
            // For mutasi, show all except maintenance and lost
            $assets = Asset::where('status', '!=', Asset::STATUS_MAINTENANCE)
                ->where('condition', '!=', Asset::CONDITION_HILANG)
                ->orderBy('code')
                ->get();
        }
            
        $users = User::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        
        return view('livewire.movements.movement-create', [
            'assets' => $assets,
            'users' => $users,
            'locations' => $locations,
        ])->layout('layouts.app');
    }
}
