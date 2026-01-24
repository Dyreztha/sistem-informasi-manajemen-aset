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
    
    public function save()
    {
        $this->validate();
        
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
            'requested_by' => Auth::id(),
        ]);
        
        session()->flash('message', 'Tiket pemeliharaan berhasil dibuat!');
        
        return $this->redirect(route('maintenances.index'), navigate: true);
    }
    
    public function render()
    {
        $assets = Asset::orderBy('code')->get();
        $technicians = User::role(['Admin Aset', 'Staff'])->orderBy('name')->get();
        
        return view('livewire.maintenances.maintenance-create', [
            'assets' => $assets,
            'technicians' => $technicians,
        ])->layout('layouts.app');
    }
}
