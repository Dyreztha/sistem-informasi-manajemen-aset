<?php

namespace App\Livewire\StockOpnames;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\StockOpname;

class StockOpnameIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $filterStatus = '';
    
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function complete($id)
    {
        $opname = StockOpname::find($id);
        if (!$opname || $opname->status !== 'in_progress') {
            return;
        }

        $totalExpected = (int) ($opname->total_expected ?? 0);
        $scanned = (int) ($opname->scanned_assets ?? 0);
        $found = (int) ($opname->found_count ?? $opname->found_assets ?? 0);
        $missing = (int) ($opname->missing_count ?? $opname->not_found_assets ?? 0);

        $opname->update([
            'status' => 'completed',
            'completed_at' => now(),
            'end_date' => now()->toDateString(),
            'total_assets' => $totalExpected,
            'scanned_assets' => $scanned,
            'found_assets' => $found,
            'not_found_assets' => $missing,
        ]);

        session()->flash('message', 'Stock opname berhasil diselesaikan.');
    }
    
    public function render()
    {
        $stockOpnames = StockOpname::with(['location', 'conductedBy'])
            ->when($this->search, function ($query) {
                $query->where('opname_number', 'like', '%' . $this->search . '%');
            })
            ->when($this->filterStatus, function ($query) {
                $query->where('status', $this->filterStatus);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);
            
        return view('livewire.stock-opnames.stock-opname-index', [
            'stockOpnames' => $stockOpnames,
        ])->layout('layouts.app');
    }
}
