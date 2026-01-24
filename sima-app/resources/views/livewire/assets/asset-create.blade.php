<div>
    <!-- Header -->
    <div class="flex items-center justify-between mb-8">
        <div class="flex items-center">
            <a href="{{ route('assets.index') }}" wire:navigate class="mr-4 p-2 text-gray-500 hover:text-gray-900 bg-white border border-gray-200 hover:bg-gray-50 rounded-xl transition-all duration-300">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">Tambah Aset Baru</h2>
                <p class="text-sm text-gray-600 mt-1">Isi form berikut untuk menambahkan aset baru</p>
            </div>
        </div>
    </div>
    
    <div class="max-w-4xl">
        <form wire:submit="save" class="space-y-6">
            <!-- Informasi Dasar -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Informasi Dasar</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Nama -->
                    <div class="md:col-span-2">
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Aset <span class="text-red-500">*</span></label>
                        <input type="text" id="name" wire:model="name" class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Laptop Thinkpad X1">
                        @error('name') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Kategori -->
                    <div>
                        <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori <span class="text-red-500">*</span></label>
                        <select id="category_id" wire:model="category_id" class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Kategori</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}">{{ $category->name }}</option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Lokasi -->
                    <div>
                        <label for="location_id" class="block text-sm font-medium text-gray-700 mb-2">Lokasi</label>
                        <select id="location_id" wire:model="location_id" class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Lokasi</option>
                            @foreach($locations as $location)
                                <option value="{{ $location->id }}">{{ $location->name }}</option>
                            @endforeach
                        </select>
                        @error('location_id') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Brand -->
                    <div>
                        <label for="brand" class="block text-sm font-medium text-gray-700 mb-2">Merk</label>
                        <input type="text" id="brand" wire:model="brand" class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: Lenovo">
                    </div>
                    
                    <!-- Model -->
                    <div>
                        <label for="model" class="block text-sm font-medium text-gray-700 mb-2">Tipe/Model</label>
                        <input type="text" id="model" wire:model="model" class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: X1 Carbon Gen 9">
                    </div>
                    
                    <!-- Serial Number -->
                    <div>
                        <label for="serial_number" class="block text-sm font-medium text-gray-700 mb-2">Nomor Seri</label>
                        <input type="text" id="serial_number" wire:model="serial_number" class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500" placeholder="Serial Number">
                    </div>
                    
                    <!-- Vendor -->
                    <div>
                        <label for="vendor_id" class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                        <select id="vendor_id" wire:model="vendor_id" class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="">Pilih Vendor</option>
                            @foreach($vendors as $vendor)
                                <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
            </div>
                    
            <!-- Informasi Keuangan -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Informasi Keuangan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Harga Perolehan -->
                    <div>
                        <label for="purchase_price" class="block text-sm font-medium text-gray-700 mb-2">Harga Perolehan <span class="text-red-500">*</span></label>
                        <div class="relative">
                            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-500 font-medium">Rp</span>
                            <input type="number" id="purchase_price" wire:model="purchase_price" class="w-full pl-12 bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500" placeholder="0">
                        </div>
                        @error('purchase_price') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    
                    <!-- Tanggal Beli -->
                    <div>
                        <label for="purchase_date" class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pembelian</label>
                        <input type="date" id="purchase_date" wire:model="purchase_date" class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                    </div>
                    
                    <!-- Warranty End -->
                    <div>
                        <label for="warranty_end_date" class="block text-sm font-medium text-gray-700 mb-2">Garansi Sampai</label>
                        <input type="date" id="warranty_end_date" wire:model="warranty_end_date" class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                    </div>
                </div>
            </div>
            
            <!-- Status & Kondisi -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Status & Kondisi</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Status -->
                    <div>
                        <label for="status" class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                        <select id="status" wire:model="status" class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="tersedia">Tersedia</option>
                            <option value="digunakan">Digunakan</option>
                            <option value="maintenance">Maintenance</option>
                            <option value="disposal">Disposal</option>
                        </select>
                    </div>
                    
                    <!-- Kondisi -->
                    <div>
                        <label for="condition" class="block text-sm font-medium text-gray-700 mb-2">Kondisi</label>
                        <select id="condition" wire:model="condition" class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                            <option value="hilang">Hilang</option>
                        </select>
                    </div>
                </div>
            </div>
            
            <!-- Keterangan -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Keterangan</h3>
                <div class="grid grid-cols-1 gap-6">
                    <!-- Description -->
                    <div>
                        <label for="description" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea id="description" wire:model="description" rows="3" class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500" placeholder="Deskripsi detail aset..."></textarea>
                    </div>
                    
                    <!-- Notes -->
                    <div>
                        <label for="notes" class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea id="notes" wire:model="notes" rows="2" class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500" placeholder="Catatan tambahan..."></textarea>
                    </div>
                </div>
            </div>
            
            <!-- Documents Upload -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Dokumen Pendukung</h3>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Upload Dokumen (Faktur, Garansi, Foto)</label>
                    <input type="file" wire:model="documents" multiple class="w-full text-sm text-gray-600 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-600 hover:file:bg-blue-100 transition-colors">
                    <p class="text-xs text-gray-500 mt-2">Maksimal 10MB per file</p>
                    @error('documents.*') <span class="text-red-500 text-sm mt-1 block">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <!-- Buttons -->
            <div class="flex justify-end space-x-4 pt-4">
                <a href="{{ route('assets.index') }}" wire:navigate class="px-6 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-medium transition-colors">
                    Batal
                </a>
                <button type="submit" class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-sm transition-all">
                    <svg wire:loading wire:target="save" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Simpan Aset
                </button>
            </div>
        </form>
    </div>
</div>
