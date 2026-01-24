<div>
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('maintenances.index') }}" wire:navigate
            class="p-2 bg-white border border-gray-300 hover:bg-gray-50 rounded-xl text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Tiket Pemeliharaan Baru</h1>
            <p class="text-gray-500 mt-1">Buat tiket pemeliharaan aset baru</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aset <span class="text-red-500">*</span></label>
                    <select wire:model="asset_id"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        <option value="">Pilih Aset</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}">{{ $asset->code }} - {{ $asset->name }}</option>
                        @endforeach
                    </select>
                    @error('asset_id') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Pemeliharaan <span class="text-red-500">*</span></label>
                    <select wire:model="type"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        <option value="">Pilih Tipe</option>
                        <option value="preventive">Preventive</option>
                        <option value="corrective">Corrective</option>
                        <option value="predictive">Predictive</option>
                    </select>
                    @error('type') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Prioritas <span class="text-red-500">*</span></label>
                    <select wire:model="priority"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        <option value="">Pilih Prioritas</option>
                        <option value="low">Low</option>
                        <option value="medium">Medium</option>
                        <option value="high">High</option>
                        <option value="critical">Critical</option>
                    </select>
                    @error('priority') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Terjadwal <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="scheduled_date"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    @error('scheduled_date') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Vendor</label>
                    <select wire:model="vendor_id"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        <option value="">Pilih Vendor</option>
                        @foreach($vendors as $vendor)
                            <option value="{{ $vendor->id }}">{{ $vendor->name }}</option>
                        @endforeach
                    </select>
                    @error('vendor_id') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Estimasi Biaya</label>
                    <input type="number" wire:model="estimated_cost" placeholder="0"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    @error('estimated_cost') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi <span class="text-red-500">*</span></label>
                    <textarea wire:model="description" rows="3" placeholder="Jelaskan kebutuhan pemeliharaan..."
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors resize-none"></textarea>
                    @error('description') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('maintenances.index') }}" wire:navigate
                    class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-300">
                    Simpan Tiket
                </button>
            </div>
        </form>
    </div>
</div>
