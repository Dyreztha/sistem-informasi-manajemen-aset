<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('assets.show', $asset) }}" wire:navigate class="mr-4 p-2 text-gray-500 hover:text-gray-900 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Edit Aset</h2>
                <p class="text-sm text-gray-600 mt-1">{{ $asset->code }} - {{ $asset->name }}</p>
            </div>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            {{ session('message') }}
        </div>
    @endif
    
    <div class="max-w-4xl">
        <form wire:submit="save" class="space-y-6">
            <!-- Informasi Dasar -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama Aset -->
                    <div class="col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Nama Aset <span class="text-red-500">*</span>
                        </label>
                        <input type="text" wire:model="name" 
                            class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                        @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kategori -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kategori <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="category_id" 
                            class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Lokasi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <select wire:model="location_id" 
                            class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->full_name }}</option>
                            @endforeach
                        </select>
                        @error('location_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Vendor -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Vendor/Supplier</label>
                        <select wire:model="vendor_id" 
                            class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                        @error('vendor_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Brand -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Merk/Brand</label>
                        <input type="text" wire:model="brand" 
                            class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                        @error('brand') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Model -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Model</label>
                        <input type="text" wire:model="model" 
                            class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                        @error('model') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Serial Number -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Seri</label>
                        <input type="text" wire:model="serial_number" 
                            class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                        @error('serial_number') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Status & Kondisi -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Status & Kondisi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Status <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="status" 
                            class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="tersedia">Tersedia</option>
                            <option value="digunakan">Digunakan</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="disposal">Disposal</option>
                        </select>
                        @error('status') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Kondisi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Kondisi <span class="text-red-500">*</span>
                        </label>
                        <select wire:model="condition" 
                            class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                            <option value="hilang">Hilang</option>
                        </select>
                        @error('condition') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Informasi Keuangan -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Informasi Keuangan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Harga Pembelian -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Harga Pembelian <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">Rp</span>
                            <input type="number" wire:model="purchase_price" step="0.01" min="0"
                                class="w-full pl-12 bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                        </div>
                        @error('purchase_price') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Pembelian -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembelian</label>
                        <input type="date" wire:model="purchase_date" 
                            class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                        @error('purchase_date') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tanggal Berakhir Garansi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Garansi Berakhir</label>
                        <input type="date" wire:model="warranty_end_date" 
                            class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                        @error('warranty_end_date') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Keterangan -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Keterangan</h3>
                <div class="grid grid-cols-1 gap-6">
                    <!-- Deskripsi -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea wire:model="description" rows="3"
                            class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error('description') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>

                    <!-- Catatan -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea wire:model="notes" rows="2"
                            class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error('notes') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
            </div>

            <!-- Dokumen -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Dokumen</h3>
                
                <!-- Existing Documents -->
                @if($existingDocuments->count() > 0)
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Dokumen Tersimpan</label>
                    <div class="space-y-2">
                        @foreach($existingDocuments as $doc)
                        <div class="flex items-center justify-between p-3 bg-gray-50 rounded-xl">
                            <span class="text-sm text-gray-600">{{ $doc->file_name }}</span>
                            <button type="button" wire:click="deleteDocument({{ $doc->id }})" 
                                wire:confirm="Hapus dokumen ini?"
                                class="text-red-600 hover:text-red-700 text-sm font-medium transition-colors">
                                Hapus
                            </button>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Upload New Documents -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tambah Dokumen Baru</label>
                    <input type="file" wire:model="documents" multiple
                        class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition-colors">
                    <p class="text-xs text-gray-500 mt-2">Maksimal 10MB per file</p>
                    @error('documents.*') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    
                    <div wire:loading wire:target="documents" class="mt-2">
                        <span class="text-sm text-blue-600">Mengupload...</span>
                    </div>
                </div>
            </div>

            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('assets.show', $asset) }}" wire:navigate
                    class="px-6 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" 
                    class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-sm transition-all"
                    wire:loading.attr="disabled">
                    <span wire:loading.remove>Simpan Perubahan</span>
                    <span wire:loading>Menyimpan...</span>
                </button>
            </div>
        </form>
    </div>
</div>
