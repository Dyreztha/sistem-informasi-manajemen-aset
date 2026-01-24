<?php

namespace App\Livewire\Maintenances;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Maintenance;
use Illuminate\Support\Facades\Auth;

class MaintenanceIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filterType = '';
    public $filterStatus = '';
    public $filterPriority = '';
    
    public $showUpdateModal = false;
    public $selectedMaintenance = null;
    public $updateStatus = '';
    public $updateNotes = '';
    public $actualCost = 0;
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function openUpdateModal($id)
    {
        $this->selectedMaintenance = Maintenance::find($id);
        if ($this->selectedMaintenance) {
            $this->updateStatus = $this->selectedMaintenance->status;
            $this->updateNotes = '';
            $this->actualCost = $this->selectedMaintenance->actual_cost ?? 0;
            $this->showUpdateModal = true;
        }
    }
    
    public function updateMaintenance()
    {
        if (!$this->selectedMaintenance) return;
        
        $oldStatus = $this->selectedMaintenance->status;
        
        $updateData = [
            'status' => $this->updateStatus,
            'actual_cost' => $this->actualCost,
        ];
        
        // Add completion date if completed
        if ($this->updateStatus === 'completed' && $oldStatus !== 'completed') {
            $updateData['completion_date'] = now();
        }
        
        // Add start date if in_progress
        if ($this->updateStatus === 'in_progress' && $oldStatus === 'pending') {
            $updateData['start_date'] = now();
        }
        
        // Add notes
        if ($this->updateNotes) {
            $updateData['technician_notes'] = ($this->selectedMaintenance->technician_notes ?? '') . "\n\n[" . now()->format('d/m/Y H:i') . "] " . $this->updateNotes;
        }
        
        $this->selectedMaintenance->update($updateData);
        
        // Update asset status based on maintenance status
        if ($this->updateStatus === 'completed') {
            $this->selectedMaintenance->asset->update([
                'status' => 'tersedia',
            ]);
        } elseif ($this->updateStatus === 'in_progress') {
            $this->selectedMaintenance->asset->update([
                'status' => 'maintenance',
            ]);
        }
        
        session()->flash('message', 'Status pemeliharaan berhasil diperbarui!');
        $this->showUpdateModal = false;
        $this->selectedMaintenance = null;
    }
    
    public function render()
    {
        $maintenances = Maintenance::with(['asset', 'requestedBy', 'assignedTo'])
            ->when($this->search, function ($query) {
                $query->where('ticket_number', 'like', '%' . $this->search . '%')
                      ->orWhereHas('asset', function ($q) {
                          $q->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('code', 'like', '%' . $this->search . '%');
                      });
            })
            ->when($this->filterType, function ($query) {
                $query->where('type', $this->filterType);
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->when($this->filterPriority, function ($query) {
                $query->where('priority', $this->filterPriority);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('livewire.maintenances.maintenance-index', [
            'maintenances' => $maintenances,
        ])->layout('layouts.app');
    }
}
