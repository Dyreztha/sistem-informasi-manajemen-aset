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
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

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
    
    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:categories,id',
            'location_id' => 'nullable|exists:locations,id',
            'vendor_id' => 'nullable|exists:vendors,id',
            'brand' => 'nullable|string|max:255',
            'model' => 'nullable|string|max:255',
            'serial_number' => [
                'nullable',
                'string',
                'max:255',
                Rule::unique('assets', 'serial_number')
                    ->ignore($this->asset->id)
                    ->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date|before_or_equal:today',
            'warranty_end_date' => 'nullable|date',
            'status' => 'required|in:tersedia,digunakan,maintenance,disposal',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',
            'notes' => 'nullable|string',
            'documents.*' => 'nullable|file|max:10240',
        ];
    }
    
    protected $messages = [
        'serial_number.unique' => 'Nomor seri sudah terdaftar pada aset lain',
        'purchase_date.before_or_equal' => 'Tanggal beli tidak boleh di masa depan',
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
        
        // Check for status change restrictions
        $originalStatus = $this->asset->getOriginal('status');
        
        // Cannot change status if asset has active movement
        if ($this->status !== $originalStatus && $this->asset->hasActiveMovement()) {
            $this->addError('status', 'Tidak dapat mengubah status karena ada transaksi sirkulasi aktif.');
            return;
        }
        
        // Cannot change status if asset has active maintenance (except to maintenance)
        if ($this->status !== $originalStatus && $this->status !== 'maintenance' && $this->asset->hasActiveMaintenance()) {
            $this->addError('status', 'Tidak dapat mengubah status karena ada tiket pemeliharaan aktif.');
            return;
        }
        
        // Additional validation for serial number (case-insensitive check)
        if ($this->serial_number) {
            $existingAsset = Asset::where('id', '!=', $this->asset->id)
                ->whereRaw('LOWER(serial_number) = ?', [strtolower($this->serial_number)])
                ->first();
            if ($existingAsset) {
                $this->addError('serial_number', "Nomor seri sudah terdaftar pada aset {$existingAsset->code} ({$existingAsset->name})");
                return;
            }
        }
        
        // Clear assigned_to if changing to tersedia
        $updateData = [
            'name' => $this->name,
            'category_id' => $this->category_id,
            'location_id' => $this->location_id ?: null,
            'vendor_id' => $this->vendor_id ?: null,
            'brand' => $this->brand,
            'model' => $this->model,
            'serial_number' => $this->serial_number ?: null,
            'description' => $this->description,
            'purchase_price' => $this->purchase_price,
            'purchase_date' => $this->purchase_date ?: null,
            'warranty_end_date' => $this->warranty_end_date ?: null,
            'status' => $this->status,
            'condition' => $this->condition,
            'notes' => $this->notes,
        ];
        
        // If status changes to tersedia, clear assignment
        if ($this->status === 'tersedia' && $originalStatus !== 'tersedia') {
            $updateData['assigned_to'] = null;
            $updateData['assigned_date'] = null;
        }
        
        $this->asset->update($updateData);
        
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
        
        session()->flash('message', 'Aset berhasil diperbarui!');
        
        return $this->redirect(route('assets.show', $this->asset), navigate: true);
    }
    
    public function deleteDocument($documentId)
    {
        $doc = AssetDocument::find($documentId);
        if ($doc && $doc->asset_id === $this->asset->id) {
            Storage::disk('public')->delete($doc->file_path);
            $doc->delete();
        }
    }
    
    public function render()
    {
        return view('livewire.assets.asset-edit', [
            'categories' => Category::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'vendors' => Vendor::orderBy('name')->get(),
            'existingDocuments' => $this->asset->documents,
        ])->layout('layouts.app');
    }
}
