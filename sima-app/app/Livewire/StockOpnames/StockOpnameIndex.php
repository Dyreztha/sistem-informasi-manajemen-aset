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
