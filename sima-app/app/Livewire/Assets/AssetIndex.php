<?php

namespace App\Livewire\Assets;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;

class AssetIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $categoryFilter = '';
    public $locationFilter = '';
    public $statusFilter = '';
    public $conditionFilter = '';
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $perPage = 10;
    
    // Modal
    public $showDeleteModal = false;
    public $deleteId = null;
    
    protected $queryString = [
        'search' => ['except' => ''],
        'categoryFilter' => ['except' => ''],
        'locationFilter' => ['except' => ''],
        'statusFilter' => ['except' => ''],
        'conditionFilter' => ['except' => ''],
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function sortBy($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }
    
    public function confirmDelete($id)
    {
        $this->deleteId = $id;
        $this->showDeleteModal = true;
    }
    
    public function deleteAsset()
    {
        Asset::find($this->deleteId)->delete();
        $this->showDeleteModal = false;
        $this->deleteId = null;
        session()->flash('message', 'Aset berhasil dihapus!');
    }
    
    public function resetFilters()
    {
        $this->search = '';
        $this->categoryFilter = '';
        $this->locationFilter = '';
        $this->statusFilter = '';
        $this->conditionFilter = '';
        $this->resetPage();
    }
    
    public function render()
    {
        $assets = Asset::with(['category', 'location', 'assignedUser'])
            ->when($this->search, function($query) {
                $query->where(function($q) {
                    $q->where('name', 'like', '%'.$this->search.'%')
                      ->orWhere('code', 'like', '%'.$this->search.'%')
                      ->orWhere('serial_number', 'like', '%'.$this->search.'%');
                });
            })
            ->when($this->categoryFilter, fn($q) => $q->where('category_id', $this->categoryFilter))
            ->when($this->locationFilter, fn($q) => $q->where('location_id', $this->locationFilter))
            ->when($this->statusFilter, fn($q) => $q->where('status', $this->statusFilter))
            ->when($this->conditionFilter, fn($q) => $q->where('condition', $this->conditionFilter))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);
        
        return view('livewire.assets.asset-index', [
            'assets' => $assets,
            'categories' => Category::all(),
            'locations' => Location::all(),
        ])->layout('layouts.app', ['header' => 'Daftar Aset']);
    }
}
