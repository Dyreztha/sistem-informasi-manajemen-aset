<?php

namespace App\Livewire\Assets;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithFileUploads;
use App\Models\Asset;
use App\Models\Category;
use App\Models\Location;
use App\Models\Vendor;
use App\Models\AssetDocument;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;

#[Layout('layouts.app')]
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

    public $documents = [];
    public $documentPreviews = [];

    // ================= VALIDATION =================
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
                Rule::unique('assets', 'serial_number')->whereNull('deleted_at'),
            ],
            'description' => 'nullable|string',
            'purchase_price' => 'required|numeric|min:0',
            'purchase_date' => 'nullable|date|before_or_equal:today',
            'warranty_end_date' => 'nullable|date|after_or_equal:purchase_date',
            'status' => 'required|in:tersedia,digunakan,maintenance,disposal',
            'condition' => 'required|in:baik,rusak_ringan,rusak_berat,hilang',

            // 🔥 pindahin validasi dokumen ke sini biar gak double
            'documents.*' => 'nullable|file|mimes:jpg,jpeg,png,pdf,doc,docx,xls,xlsx|max:10240',
        ];
    }

    // ================= PREVIEW =================
    public function updatedDocuments()
    {
        $this->validateOnly('documents.*');
        $this->generateDocumentPreviews();
    }

    private function generateDocumentPreviews()
    {
        $this->documentPreviews = [];

        foreach ($this->documents as $key => $file) {
            $this->documentPreviews[$key] = [
                'name' => $file->getClientOriginalName(),
                'size' => $this->formatBytes($file->getSize()),
                'type' => $file->getMimeType(),

                // 🔥 FIX: pakai temporaryUrl (lebih ringan & gak pecah)
                'url' => $file->temporaryUrl(),
            ];
        }
    }

    private function formatBytes($bytes, $precision = 2)
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $pow = floor(log($bytes ?: 1, 1024));
        $pow = min($pow, count($units) - 1);
        return round($bytes / (1024 ** $pow), $precision) . ' ' . $units[$pow];
    }

    public function deleteDocument($key)
    {
        unset($this->documents[$key]);
        $this->documents = array_values($this->documents);
        $this->generateDocumentPreviews();
    }

    // ================= SAVE =================
    public function save()
    {
        Log::info('Asset save started');

        // VALIDASI
        $this->validate();

        // 🔥 FIX: cek serial number (case insensitive + ignore soft delete)
        if ($this->serial_number) {
            $exists = Asset::whereRaw('LOWER(serial_number) = ?', [strtolower($this->serial_number)])
                ->whereNull('deleted_at')
                ->exists();

            if ($exists) {
                $this->addError('serial_number', 'Nomor seri sudah terdaftar');
                return;
            }
        }

        try {
            // CREATE ASSET
            $asset = Asset::create([
                'name' => $this->name,
                'category_id' => $this->category_id,
                'location_id' => $this->location_id ?: null,
                'vendor_id' => $this->vendor_id ?: null,
                'brand' => $this->brand,
                'model' => $this->model,
                'serial_number' => $this->serial_number ?: null,
                'description' => $this->description,
                'purchase_price' => $this->purchase_price,
                'current_value' => $this->purchase_price,
                'purchase_date' => $this->purchase_date ?: null,
                'warranty_end_date' => $this->warranty_end_date ?: null,
                'status' => $this->status,
                'condition' => $this->condition,
                'notes' => $this->notes,
            ]);

            Log::info('Asset created', ['id' => $asset->id]);

            // ================= UPLOAD FILE =================
            if (!empty($this->documents)) {
                foreach ($this->documents as $file) {

                    $path = $file->store('asset-documents', 'public');

                    AssetDocument::create([
                        'asset_id' => $asset->id,
                        'title' => $file->getClientOriginalName(),
                        'type' => 'other',
                        'file_path' => $path,
                        'file_name' => $file->getClientOriginalName(),
                        'mime_type' => $file->getMimeType(),
                        'file_size' => $file->getSize(),
                        'uploaded_by' => Auth::id(),
                    ]);
                }
            }

        } catch (\Exception $e) {
            Log::error('Asset creation failed: ' . $e->getMessage());
            session()->flash('error', 'Gagal menyimpan aset');
            return;
        }

        // RESET
        $this->reset(['documents', 'documentPreviews']);

        session()->flash('message', 'Aset berhasil ditambahkan');

        return redirect()->route('assets.index');
    }

    public function render()
    {
        return view('livewire.assets.asset-create', [
            'categories' => Category::orderBy('name')->get(),
            'locations' => Location::orderBy('name')->get(),
            'vendors' => Vendor::orderBy('name')->get(),
        ]);
    }
}
