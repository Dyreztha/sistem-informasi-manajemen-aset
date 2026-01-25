<?php

namespace App\Livewire\Maintenances;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asset;
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
    public $resultCondition = 'baik';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function openUpdateModal($id)
    {
        $this->selectedMaintenance = Maintenance::with('asset')->find($id);
        if ($this->selectedMaintenance) {
            $this->updateStatus = $this->selectedMaintenance->status;
            $this->updateNotes = '';
            $this->actualCost = $this->selectedMaintenance->actual_cost ?? $this->selectedMaintenance->estimated_cost ?? 0;
            $this->resultCondition = $this->selectedMaintenance->asset->condition ?? 'baik';
            $this->showUpdateModal = true;
        }
    }
    
    public function updateMaintenance()
    {
        if (!$this->selectedMaintenance) return;
        
        $oldStatus = $this->selectedMaintenance->status;
        $asset = $this->selectedMaintenance->asset;
        
        // Validate status transition
        $validTransitions = [
            'pending' => ['in_progress', 'cancelled'],
            'in_progress' => ['completed', 'cancelled'],
            'completed' => [],
            'cancelled' => [],
        ];
        
        if (!in_array($this->updateStatus, $validTransitions[$oldStatus] ?? [])) {
            if ($this->updateStatus !== $oldStatus) {
                session()->flash('error', 'Transisi status tidak valid.');
                return;
            }
        }
        
        $updateData = [
            'status' => $this->updateStatus,
            'actual_cost' => $this->actualCost,
        ];
        
        // Track previous asset status before maintenance
        $previousAssetStatus = $asset->status;
        
        // Add completion date if completed
        if ($this->updateStatus === 'completed' && $oldStatus !== 'completed') {
            $updateData['completed_date'] = now();
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
        if ($this->updateStatus === 'in_progress') {
            // Set asset to maintenance status
            $asset->update([
                'status' => Asset::STATUS_MAINTENANCE,
            ]);
        } elseif ($this->updateStatus === 'completed') {
            // Check if there are other active maintenance tickets
            $otherActiveMaintenance = $asset->maintenances()
                ->where('id', '!=', $this->selectedMaintenance->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->exists();
            
            if (!$otherActiveMaintenance) {
                // Determine new status based on whether asset was borrowed before
                $wasInUse = $asset->assigned_to !== null;
                
                $asset->update([
                    'status' => $wasInUse ? Asset::STATUS_DIGUNAKAN : Asset::STATUS_TERSEDIA,
                    'condition' => $this->resultCondition,
                ]);
            } else {
                // Just update condition
                $asset->update([
                    'condition' => $this->resultCondition,
                ]);
            }
        } elseif ($this->updateStatus === 'cancelled') {
            // Check if there are other active maintenance tickets
            $otherActiveMaintenance = $asset->maintenances()
                ->where('id', '!=', $this->selectedMaintenance->id)
                ->whereIn('status', ['pending', 'in_progress'])
                ->exists();
            
            if (!$otherActiveMaintenance && $asset->status === Asset::STATUS_MAINTENANCE) {
                // Restore to previous status
                $wasInUse = $asset->assigned_to !== null;
                $asset->update([
                    'status' => $wasInUse ? Asset::STATUS_DIGUNAKAN : Asset::STATUS_TERSEDIA,
                ]);
            }
        }
        
        session()->flash('message', 'Status pemeliharaan berhasil diperbarui!');
        $this->showUpdateModal = false;
        $this->selectedMaintenance = null;
    }
    
    public function render()
    {
        $maintenances = Maintenance::with(['asset', 'reporter', 'assignee'])
            ->when($this->search, function ($query) {
                $query->where('ticket_number', 'like', '%' . $this->search . '%')
                      ->orWhere('title', 'like', '%' . $this->search . '%')
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
