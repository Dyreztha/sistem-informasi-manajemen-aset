<?php

namespace App\Livewire\Vendors;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Vendor;

class VendorIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $vendorId = null;
    
    public $name = '';
    public $code = '';
    public $contact_person = '';
    public $phone = '';
    public $email = '';
    public $address = '';
    public $description = '';
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:vendors,code',
        'contact_person' => 'nullable|string|max:255',
        'phone' => 'nullable|string|max:50',
        'email' => 'nullable|email|max:255',
        'address' => 'nullable|string',
        'description' => 'nullable|string',
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function openCreateModal()
    {
        $this->reset(['name', 'code', 'contact_person', 'phone', 'email', 'address', 'description', 'vendorId']);
        $this->editMode = false;
        $this->showModal = true;
    }
    
    public function openEditModal(Vendor $vendor)
    {
        $this->vendorId = $vendor->id;
        $this->name = $vendor->name;
        $this->code = $vendor->code;
        $this->contact_person = $vendor->contact_person;
        $this->phone = $vendor->phone;
        $this->email = $vendor->email;
        $this->address = $vendor->address;
        $this->description = $vendor->description;
        $this->editMode = true;
        $this->showModal = true;
    }
    
    public function save()
    {
        $rules = $this->rules;
        if ($this->editMode) {
            $rules['code'] = 'required|string|max:50|unique:vendors,code,' . $this->vendorId;
        }
        $this->validate($rules);
        
        if ($this->editMode) {
            $vendor = Vendor::find($this->vendorId);
            $vendor->update([
                'name' => $this->name,
                'code' => $this->code,
                'contact_person' => $this->contact_person,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Vendor berhasil diperbarui!');
        } else {
            Vendor::create([
                'name' => $this->name,
                'code' => $this->code,
                'contact_person' => $this->contact_person,
                'phone' => $this->phone,
                'email' => $this->email,
                'address' => $this->address,
                'description' => $this->description,
            ]);
            session()->flash('message', 'Vendor berhasil ditambahkan!');
        }
        
        $this->showModal = false;
        $this->reset(['name', 'code', 'contact_person', 'phone', 'email', 'address', 'description', 'vendorId']);
    }
    
    public function confirmDelete($id)
    {
        $this->vendorId = $id;
        $this->showDeleteModal = true;
    }
    
    public function delete()
    {
        $vendor = Vendor::find($this->vendorId);
        if ($vendor) {
            if ($vendor->assets()->count() > 0) {
                session()->flash('error', 'Vendor tidak dapat dihapus karena masih memiliki aset!');
            } else {
                $vendor->delete();
                session()->flash('message', 'Vendor berhasil dihapus!');
            }
        }
        $this->showDeleteModal = false;
    }
    
    public function render()
    {
        $vendors = Vendor::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%')
                      ->orWhere('contact_person', 'like', '%' . $this->search . '%');
            })
            ->withCount('assets')
            ->orderBy('name')
            ->paginate(10);
            
        return view('livewire.vendors.vendor-index', [
            'vendors' => $vendors,
        ])->layout('layouts.app');
    }
}
