<?php

namespace App\Livewire\Movements;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asset;
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
        $this->selectedMovement = AssetMovement::with('asset')->find($id);
        if ($this->selectedMovement && $this->selectedMovement->type === 'peminjaman' && $this->selectedMovement->status === 'approved' && !$this->selectedMovement->actual_return_date) {
            $this->showReturnModal = true;
            $this->returnNotes = '';
            $this->returnCondition = $this->selectedMovement->asset->condition ?? 'baik';
        }
    }
    
    public function processReturn()
    {
        if (!$this->selectedMovement) return;
        
        $asset = $this->selectedMovement->asset;
        
        // Check if asset is under maintenance
        if ($asset->hasActiveMaintenance()) {
            session()->flash('error', 'Aset sedang dalam pemeliharaan. Selesaikan pemeliharaan terlebih dahulu.');
            $this->showReturnModal = false;
            return;
        }
        
        $this->selectedMovement->update([
            'status' => 'approved',
            'actual_return_date' => now(),
            'notes' => $this->selectedMovement->notes 
                ? $this->selectedMovement->notes . "\n\n[Pengembalian " . now()->format('d/m/Y H:i') . "]: " . $this->returnNotes
                : "[Pengembalian " . now()->format('d/m/Y H:i') . "]: " . $this->returnNotes,
        ]);
        
        // Update asset status & condition
        $asset->update([
            'status' => Asset::STATUS_TERSEDIA,
            'condition' => $this->returnCondition,
            'assigned_to' => null,
            'assigned_date' => null,
        ]);
        
        session()->flash('message', 'Aset berhasil dikembalikan!');
        $this->showReturnModal = false;
        $this->selectedMovement = null;
    }
    
    public function approveMovement($id)
    {
        $movement = AssetMovement::with('asset')->find($id);
        if (!$movement || $movement->status !== 'pending') {
            session()->flash('error', 'Transaksi tidak valid atau sudah diproses.');
            return;
        }
        
        $asset = $movement->asset;
        
        // Re-validate asset availability at approval time
        if ($movement->type === 'peminjaman') {
            if (!$asset->isAvailable()) {
                $reason = $asset->getUnavailabilityReason() ?? 'Aset tidak tersedia';
                session()->flash('error', "Tidak dapat menyetujui: {$reason}");
                return;
            }
            
            if ($asset->hasActiveMaintenance()) {
                session()->flash('error', 'Tidak dapat menyetujui: Aset memiliki tiket pemeliharaan aktif.');
                return;
            }
        }
        
        if ($movement->type === 'mutasi') {
            if ($asset->isUnderMaintenance()) {
                session()->flash('error', 'Tidak dapat menyetujui: Aset sedang dalam pemeliharaan.');
                return;
            }
        }
        
        $movement->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);
        
        // Update asset based on movement type
        if ($movement->type === 'peminjaman') {
            $asset->update([
                'status' => Asset::STATUS_DIGUNAKAN,
                'assigned_to' => $movement->to_user_id,
                'assigned_date' => now(),
            ]);
        } elseif ($movement->type === 'mutasi') {
            $asset->update([
                'location_id' => $movement->to_location_id,
            ]);
        } elseif ($movement->type === 'pengembalian') {
            $asset->update([
                'status' => Asset::STATUS_TERSEDIA,
                'assigned_to' => null,
                'assigned_date' => null,
            ]);
        }
        
        session()->flash('message', 'Pergerakan aset disetujui!');
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
        $movements = AssetMovement::with(['asset', 'fromUser', 'toUser', 'fromLocation', 'toLocation', 'approver', 'creator'])
            ->when($this->search, function ($query) {
                $query->where('bast_number', 'like', '%' . $this->search . '%')
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
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('livewire.movements.movement-index', [
            'movements' => $movements,
        ])->layout('layouts.app');
    }
}
