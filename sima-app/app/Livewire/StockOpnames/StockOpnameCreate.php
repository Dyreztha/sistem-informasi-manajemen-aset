<?php

namespace App\Livewire\StockOpnames;

use Livewire\Component;
use App\Models\StockOpname;
use App\Models\Location;
use Illuminate\Support\Facades\Auth;

class StockOpnameCreate extends Component
{
    public $title = '';
    public $location_id = '';
    public $opname_date = '';
    public $notes = '';
    
    protected $rules = [
        'title' => 'required|string|max:255',
        'location_id' => 'nullable|exists:locations,id',
        'opname_date' => 'required|date',
        'notes' => 'nullable|string',
    ];
    
    public function mount()
    {
        $this->opname_date = now()->format('Y-m-d');
    }
    
    public function save()
    {
        $this->validate();
        
        $opname = StockOpname::create([
            'title' => $this->title,
            'location_id' => $this->location_id ?: null,
            'opname_date' => $this->opname_date,
            'start_date' => $this->opname_date,
            'notes' => $this->notes,
            'status' => 'planned',
            'created_by' => Auth::id(),
            'conducted_by' => Auth::id(),
        ]);
        
        session()->flash('message', 'Stock opname berhasil dibuat. Silakan mulai scanning.');
        
        return $this->redirect(route('stock-opnames.scan', $opname), navigate: true);
    }
    
    public function render()
    {
        $locations = Location::orderBy('name')->get();
        
        return view('livewire.stock-opnames.stock-opname-create', [
            'locations' => $locations,
        ])->layout('layouts.app');
    }
}
