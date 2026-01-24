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

class AssetEdit extends Component
{
    use WithFileUploads;
    
    public Asset $asset;
    
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
        'warranty_end_date' => 'nullable|date',
        'status' => 'required|in:tersedia,digunakan,maintenance,disposal',
        'condition' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
        'notes' => 'nullable|string',
        'documents.*' => 'nullable|file|max:10240',
    ];
    
    public function mount(Asset $asset)
    {
        $this->asset = $asset;
        $this->name = $asset->name;
        $this->category_id = $asset->category_id;
        $this->location_id = $asset->location_id;
        $this->vendor_id = $asset->vendor_id;
        $this->brand = $asset->brand;
        $this->model = $asset->model;
        $this->serial_number = $asset->serial_number;
        $this->description = $asset->description;
        $this->purchase_price = $asset->purchase_price;
        $this->purchase_date = $asset->purchase_date?->format('Y-m-d');
        $this->warranty_end_date = $asset->warranty_end_date?->format('Y-m-d');
        $this->status = $asset->status;
        $this->condition = $asset->condition;
        $this->notes = $asset->notes;
    }
    
    public function save()
    {
        $this->validate();
        
        $this->asset->update([
            'name' => $this->name,
            'category_id' => $this->category_id,
            'location_id' => $this->location_id ?: null,
            'vendor_id' => $this->vendor_id ?: null,
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number,
            'description' => $this->description,
            'purchase_price' => $this->purchase_price,
            'purchase_date' => $this->purchase_date ?: null,
            'warranty_end_date' => $this->warranty_end_date ?: null,
            'status' => $this->status,
            'condition' => $this->condition,
            'notes' => $this->notes,
        ]);
        
        // Upload new documents
        if (!empty($this->documents)) {
            foreach ($this->documents as $document) {
                $path = $document->store('asset-documents', 'public');
                
                AssetDocument::create([
                    'asset_id' => $this->asset->id,
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
        
        // Recalculate depreciation
        $this->asset->updateCurrentValue();
        
        session()->flash('message', 'Aset berhasil diperbarui!');
        
        return $this->redirect(route('assets.show', $this->asset), navigate: true);
    }
    
    public function deleteDocument($documentId)
    {
        $doc = AssetDocument::find($documentId);
        if ($doc && $doc->asset_id === $this->asset->id) {
            \Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }
    }
    
    public function render()
    {
        return view('livewire.assets.asset-edit', [
            'categories' => Category::all(),
            'locations' => Location::all(),
            'vendors' => Vendor::all(),
            'existingDocuments' => $this->asset->documents,
        ])->layout('layouts.app');
    }
}
