<?php

namespace App\Livewire\Assets;

use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\Vendor;
use App\Models\AssetDocument;
use Illuminate\Support\Facades\Auth;

class AssetCreate extends Component
{
    use WithFileUploads;
    
    public $name = '';
    public $category_id = '';
    public $location_id = '';
    public $vendor_id = '';
    public $brand = '';
    public $model = '';
    public $serial_number = '';
    public $description = '';
    public $purchase_price = 0;
    public $purchase_date = '';
    public $warranty_end_date = '';
    public $status = 'tersedia';
    public $condition = 'baik';
    public $notes = '';
    
    // Documents
    public $documents = [];
    
    protected $rules = [
        'name' => 'required|string|max:255',
        'category_id' => 'required|exists:categories,id',
        'location_id' => 'nullable|exists:locations,id',
        'vendor_id' => 'nullable|exists:vendors,id',
        'brand' => 'nullable|string|max:255',
        'model' => 'nullable|string|max:255',
        'serial_number' => 'nullable|string|max:255',
        'description' => 'nullable|string',
        'purchase_price' => 'required|numeric|min:0',
        'purchase_date' => 'nullable|date',
        'warranty_end_date' => 'nullable|date|after_or_equal:purchase_date',
        'status' => 'required|in:tersedia,digunakan,maintenance,disposal',
        'condition' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
        'notes' => 'nullable|string',
        'documents.*' => 'nullable|file|max:10240',
    ];
    
    protected $messages = [
        'name.required' => 'Nama aset harus diisi',
        'category_id.required' => 'Kategori harus dipilih',
        'purchase_price.required' => 'Harga perolehan harus diisi',
        'purchase_price.numeric' => 'Harga harus berupa angka',
    ];
    
    public function save()
    {
        $this->validate();
        
        $asset = Asset::create([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'location_id' => $this->location_id ?: null,
            'vendor_id' => $this->vendor_id ?: null,
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'description' => $this->description,
            'purchase_price' => $this->purchase_price,
            'current_value' => $this->purchase_price,
            'purchase_date' => $this->purchase_date ?: null,
            'warranty_end_date' => $this->warranty_end_date ?: null,
            'status' => $this->status,
            'condition' => $this->condition,
            'notes' => $this->notes,
        ]);
        
        // Upload documents
        if (!empty($this->documents)) {
            foreach ($this->documents as $document) {
                $path = $document->store('asset-documents', 'public');
                
                AssetDocument::create([
                    'asset_id' => $asset->id,
                    'title' => $document->getClientOriginalName(),
                    'type' => 'other',
                    'file_path' => $path,
                    'file_name' => $document->getClientOriginalName(),
                    'mime_type' => $document->getMimeType(),
                    'file_size' => $document->getSize(),
                    'uploaded_by' => Auth::id(),
                ]);
            }
        }
        
        // Calculate depreciation
        $asset->updateCurrentValue();
        
        session()->flash('message', 'Aset berhasil ditambahkan!');
        
        return $this->redirect(route('assets.index'), navigate: true);
    }
    
    public function render()
    {
        return view('livewire.assets.asset-create', [
            'categories' => Category::all(),
            'locations' => Location::all(),
            'vendors' => Vendor::all(),
        ])->layout('layouts.app');
    }
}
