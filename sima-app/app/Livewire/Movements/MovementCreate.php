<?php

namespace App\Livewire\Movements;

use Livewire\Component;
use App\Models\Asset;
use App\Models\AssetMovement;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class MovementCreate extends Component
{
    public $type = 'peminjaman';
    public $asset_id = '';
    public $from_user_id = '';
    public $to_user_id = '';
    public $from_location_id = '';
    public $to_location_id = '';
    public $movement_date = '';
    public $expected_return_date = '';
    public $reason = '';
    public $notes = '';
    
    protected function rules()
    {
        $rules = [
            'type' => 'required|in:peminjaman,mutasi',
            'asset_id' => 'required|exists:assets,id',
            'movement_date' => 'required|date',
            'reason' => 'required|string|max:500',
            'notes' => 'nullable|string',
        ];
        
        if ($this->type === 'peminjaman') {
            $rules['to_user_id'] = 'required|exists:users,id';
            $rules['expected_return_date'] = 'required|date|after:movement_date';
        }
        
        if ($this->type === 'mutasi') {
            $rules['to_location_id'] = 'required|exists:locations,id';
        }
        
        return $rules;
    }
    
    public function mount()
    {
        $this->movement_date = now()->format('Y-m-d');
    }
    
    public function updatedAssetId()
    {
        if ($this->asset_id) {
            $asset = Asset::find($this->asset_id);
            if ($asset) {
                $this->from_user_id = $asset->current_user_id ?? '';
                $this->from_location_id = $asset->location_id ?? '';
            }
        }
    }
    
    public function save()
    {
        $this->validate();
        
        $asset = Asset::find($this->asset_id);
        
        // Validate asset is available
        if ($this->type === 'peminjaman' && $asset->status !== 'tersedia') {
            $this->addError('asset_id', 'Aset tidak tersedia untuk dipinjam.');
            return;
        }
        
        AssetMovement::create([
            'asset_id' => $this->asset_id,
            'type' => $this->type,
            'from_user_id' => $this->from_user_id ?: null,
            'to_user_id' => $this->type === 'peminjaman' ? $this->to_user_id : null,
            'from_location_id' => $this->from_location_id ?: null,
            'to_location_id' => $this->type === 'mutasi' ? $this->to_location_id : $asset->location_id,
            'movement_date' => $this->movement_date,
            'expected_return_date' => $this->type === 'peminjaman' ? $this->expected_return_date : null,
            'reason' => $this->reason,
            'notes' => $this->notes,
            'status' => 'pending',
            'created_by' => Auth::id(),
        ]);
        
        session()->flash('message', 'Transaksi sirkulasi berhasil dibuat dan menunggu persetujuan.');
        
        return $this->redirect(route('movements.index'), navigate: true);
    }
    
    public function render()
    {
        $assets = Asset::where('status', 'tersedia')
            ->orWhere('status', 'digunakan')
            ->orderBy('code')
            ->get();
            
        $users = User::orderBy('name')->get();
        $locations = Location::orderBy('name')->get();
        
        return view('livewire.movements.movement-create', [
            'assets' => $assets,
            'users' => $users,
            'locations' => $locations,
        ])->layout('layouts.app');
    }
}
