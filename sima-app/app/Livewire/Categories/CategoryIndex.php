<?php

namespace App\Livewire\Categories;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Category;

class CategoryIndex extends Component
{
    use WithPagination;
    
    public $search = '';
    public $showModal = false;
    public $showDeleteModal = false;
    public $editMode = false;
    public $categoryId = null;
    
    public $name = '';
    public $code = '';
    public $description = '';
    public $depreciation_rate = 0;
    public $depreciation_method = 'straight_line';
    public $useful_life_years = 5;
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'code' => 'required|string|max:50|unique:categories,code',
        'description' => 'nullable|string',
        'depreciation_rate' => 'required|numeric|min:0|max:100',
        'depreciation_method' => 'required|in:straight_line,declining_balance',
        'useful_life_years' => 'required|integer|min:1',
    ];
    
    public function updatingSearch()
    {
        $this->resetPage();
    }
    
    public function openCreateModal()
    {
        $this->reset(['name', 'code', 'description', 'depreciation_rate', 'depreciation_method', 'useful_life_years', 'categoryId']);
        $this->editMode = false;
        $this->showModal = true;
    }
    
    public function openEditModal(Category $category)
    {
        $this->categoryId = $category->id;
        $this->name = $category->name;
        $this->code = $category->code;
        $this->description = $category->description;
        $this->depreciation_rate = $category->depreciation_rate;
        $this->depreciation_method = $category->depreciation_method;
        $this->useful_life_years = $category->useful_life_years;
        $this->editMode = true;
        $this->showModal = true;
    }
    
    public function save()
    {
        $rules = $this->rules;
        if ($this->editMode) {
            $rules['code'] = 'required|string|max:50|unique:categories,code,' . $this->categoryId;
        }
        $this->validate($rules);
        
        if ($this->editMode) {
            $category = Category::find($this->categoryId);
            $category->update([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'depreciation_rate' => $this->depreciation_rate,
                'depreciation_method' => $this->depreciation_method,
                'useful_life_years' => $this->useful_life_years,
            ]);
            session()->flash('message', 'Kategori berhasil diperbarui!');
        } else {
            Category::create([
                'name' => $this->name,
                'code' => $this->code,
                'description' => $this->description,
                'depreciation_rate' => $this->depreciation_rate,
                'depreciation_method' => $this->depreciation_method,
                'useful_life_years' => $this->useful_life_years,
            ]);
            session()->flash('message', 'Kategori berhasil ditambahkan!');
        }
        
        $this->showModal = false;
        $this->reset(['name', 'code', 'description', 'depreciation_rate', 'depreciation_method', 'useful_life_years', 'categoryId']);
    }
    
    public function confirmDelete($id)
    {
        $this->categoryId = $id;
        $this->showDeleteModal = true;
    }
    
    public function delete()
    {
        $category = Category::find($this->categoryId);
        if ($category) {
            if ($category->assets()->count() > 0) {
                session()->flash('error', 'Kategori tidak dapat dihapus karena masih memiliki aset!');
            } else {
                $category->delete();
                session()->flash('message', 'Kategori berhasil dihapus!');
            }
        }
        $this->showDeleteModal = false;
    }
    
    public function render()
    {
        $categories = Category::when($this->search, function ($query) {
                $query->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('code', 'like', '%' . $this->search . '%');
            })
            ->withCount('assets')
            ->orderBy('name')
            ->paginate(10);
            
        return view('livewire.categories.category-index', [
            'categories' => $categories,
        ])->layout('layouts.app');
    }
}
