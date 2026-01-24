<?php

namespace App\Livewire\Locations;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Location;

class LocationIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $locationId = null;
    
    public $name = '';
    public $code = '';
    public $parent_id = '';
    public $building = '';
    public $floor = '';
    public $room = '';
    public $description = '';
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:locations,code',
        'parent_id' => 'nullable|exists:locations,id',
        'building' => 'nullable|string|max:255',
        'floor' => 'nullable|string|max:50',
        'room' => 'nullable|string|max:100',
        'description' => 'nullable|string',
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function openCreateModal()
    {
        $this->reset(['name', 'code', 'parent_id', 'building', 'floor', 'room', 'description', 'locationId']);
        $this->editMode = false;
        $this->showModal = true;
    }
    
    public function openEditModal(Location $location)
    {
        $this->locationId = $location->id;
        $this->name = $location->name;
        $this->code = $location->code;
        $this->parent_id = $location->parent_id;
        $this->building = $location->building;
        $this->floor = $location->floor;
        $this->room = $location->room;
        $this->description = $location->description;
        $this->editMode = true;
        $this->showModal = true;
    }
    
    public function save()
    {
        $rules = $this->rules;
        if ($this->editMode) {
            $rules['code'] = 'required|string|max:50|unique:locations,code,' . $this->locationId;
        }
        $this->validate($rules);
        
        if ($this->editMode) {
            $location = Location::find($this->locationId);
            $location->update([
                'name' => $this->name,
                'code' => $this->code,
                'parent_id' => $this->parent_id ?: null,
                'building' => $this->building,
                'floor' => $this->floor,
                'room' => $this->room,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Lokasi berhasil diperbarui!');
        } else {
            Location::create([
                'name' => $this->name,
                'code' => $this->code,
                'parent_id' => $this->parent_id ?: null,
                'building' => $this->building,
                'floor' => $this->floor,
                'room' => $this->room,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Lokasi berhasil ditambahkan!');
        }
        
        $this->showModal = false;
        $this->reset(['name', 'code', 'parent_id', 'building', 'floor', 'room', 'description', 'locationId']);
    }
    
    public function confirmDelete($id)
    {
        $this->locationId = $id;
        $this->showDeleteModal = true;
    }
    
    public function delete()
    {
        $location = Location::find($this->locationId);
        if ($location) {
            if ($location->assets()->count() > 0) {
                session()->flash('error', 'Lokasi tidak dapat dihapus karena masih memiliki aset!');
            } elseif ($location->children()->count() > 0) {
                session()->flash('error', 'Lokasi tidak dapat dihapus karena masih memiliki sub-lokasi!');
            } else {
                $location->delete();
                session()->flash('message', 'Lokasi berhasil dihapus!');
            }
        }
        $this->showDeleteModal = false;
    }
    
    public function render()
    {
        $locations = Location::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('building', 'like', '%' . $this->search . '%');
            })
            ->with('parent')
            ->withCount('assets')
            ->orderBy('name')
            ->paginate(10);
        
        $parentLocations = Location::whereNull('parent_id')
            ->orWhereHas('parent', function($q) {
                $q->whereNull('parent_id');
            })
            ->when($this->locationId, function($query) {
                $query->where('id', '!=', $this->locationId);
            })
            ->get();
            
        return view('livewire.locations.location-index', [
            'locations' => $locations,
            'parentLocations' => $parentLocations,
        ])->layout('layouts.app');
    }
}
