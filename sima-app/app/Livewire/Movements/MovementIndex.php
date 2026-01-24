<?php

namespace App\Livewire\Movements;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\AssetMovement;
use Illuminate\Support\Facades\Auth;

class MovementIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filterType = '';
    public $filterStatus = '';
    public $showReturnModal = false;
    public $selectedMovement = null;
    public $returnNotes = '';
    public $returnCondition = 'baik';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function updatingFilterType()
    {
        $this->resetPage();
    }
    
    public function updatingFilterStatus()
    {
        $this->resetPage();
    }
    
    public function openReturnModal($id)
    {
        $this->selectedMovement = AssetMovement::find($id);
        if ($this->selectedMovement && $this->selectedMovement->type === 'peminjaman' && $this->selectedMovement->status === 'active') {
            $this->showReturnModal = true;
            $this->returnNotes = '';
            $this->returnCondition = 'baik';
        }
    }
    
    public function processReturn()
    {
        if (!$this->selectedMovement) return;
        
        $this->selectedMovement->update([
            'status' => 'returned',
            'actual_return_date' => now(),
            'notes' => $this->selectedMovement->notes . "\n\nPengembalian: " . $this->returnNotes,
        ]);
        
        // Update asset status & condition
        $this->selectedMovement->asset->update([
            'status' => 'tersedia',
            'condition' => $this->returnCondition,
            'current_user_id' => null,
            'location_id' => $this->selectedMovement->from_location_id,
        ]);
        
        session()->flash('message', 'Aset berhasil dikembalikan!');
        $this->showReturnModal = false;
        $this->selectedMovement = null;
    }
    
    public function approveMovement($id)
    {
        $movement = AssetMovement::find($id);
        if ($movement && $movement->status === 'pending') {
            $movement->update([
                'status' => 'active',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
            
            // Update asset
            $movement->asset->update([
                'status' => 'digunakan',
                'current_user_id' => $movement->to_user_id,
                'location_id' => $movement->to_location_id,
            ]);
            
            session()->flash('message', 'Pergerakan aset disetujui!');
        }
    }
    
    public function rejectMovement($id)
    {
        $movement = AssetMovement::find($id);
        if ($movement && $movement->status === 'pending') {
            $movement->update([
                'status' => 'rejected',
                'approved_by' => Auth::id(),
                'approved_at' => now(),
            ]);
            
            session()->flash('message', 'Pergerakan aset ditolak.');
        }
    }
    
    public function render()
    {
        $movements = AssetMovement::with(['asset', 'fromUser', 'toUser', 'fromLocation', 'toLocation', 'approvedBy'])
            ->when($this->search, function ($query) {
                $query->whereHas('asset', function ($q) {
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
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('livewire.movements.movement-index', [
            'movements' => $movements,
        ])->layout('layouts.app');
    }
}
