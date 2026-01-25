<div>
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('movements.index') }}" wire:navigate
            class="p-2 bg-white border border-gray-300 hover:bg-gray-50 rounded-xl text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Transaksi Baru</h1>
            <p class="text-gray-500 mt-1">Buat transaksi pergerakan aset baru</p>
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

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Form -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-8">
        <form wire:submit="save">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Transaksi <span class="text-red-500">*</span></label>
                    <select wire:model.live="type"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        <option value="">Pilih Tipe</option>
                        <option value="peminjaman">Peminjaman</option>
                        <option value="mutasi">Mutasi Lokasi</option>
                        <option value="pengembalian">Pengembalian</option>
                    </select>
                    @error('type') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Aset <span class="text-red-500">*</span></label>
                    <select wire:model.live="asset_id"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        <option value="">Pilih Aset</option>
                        @foreach($assets as $asset)
                            <option value="{{ $asset->id }}">
                                {{ $asset->code }} - {{ $asset->name }}
                                @if($asset->status !== 'tersedia')
                                    ({{ ucfirst($asset->status) }})
                                @endif
                            </option>
                        @endforeach
                    </select>
                    @error('asset_id') <span class="text-sm text-red-500 mt-1 block">{{ $message }}</span> @enderror
                    
                    <!-- Asset Info Card -->
                    @if($selectedAssetInfo)
                        <div class="mt-3 p-4 rounded-xl border {{ $selectedAssetInfo['is_available'] ? 'bg-green-50 border-green-200' : 'bg-yellow-50 border-yellow-200' }}">
                            <div class="flex items-start gap-3">
                                <div class="flex-shrink-0">
                                    @if($selectedAssetInfo['is_available'])
                                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    @else
                                        <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                                        </svg>
                                    @endif
                                </div>
                                <div class="flex-1 min-w-0">
                                    <p class="text-sm font-medium {{ $selectedAssetInfo['is_available'] ? 'text-green-800' : 'text-yellow-800' }}">
                                        {{ $selectedAssetInfo['code'] }} - {{ $selectedAssetInfo['name'] }}
                                    </p>
                                    <div class="mt-1 text-sm {{ $selectedAssetInfo['is_available'] ? 'text-green-700' : 'text-yellow-700' }}">
                                        <span class="inline-flex items-center gap-1">
                                            <span class="font-medium">Status:</span> 
                                            <span class="capitalize">{{ $selectedAssetInfo['status'] }}</span>
                                        </span>
                                        <span class="mx-2">•</span>
                                        <span class="inline-flex items-center gap-1">
                                            <span class="font-medium">Kondisi:</span> 
                                            <span class="capitalize">{{ str_replace('_', ' ', $selectedAssetInfo['condition']) }}</span>
                                        </span>
                                    </div>
                                    @if($selectedAssetInfo['assigned_to'])
                                        <p class="mt-1 text-sm {{ $selectedAssetInfo['is_available'] ? 'text-green-700' : 'text-yellow-700' }}">
                                            <span class="font-medium">Digunakan oleh:</span> {{ $selectedAssetInfo['assigned_to'] }}
                                        </p>
                                    @endif
                                    @if($selectedAssetInfo['location'])
                                        <p class="text-sm {{ $selectedAssetInfo['is_available'] ? 'text-green-700' : 'text-yellow-700' }}">
                                            <span class="font-medium">Lokasi:</span> {{ $selectedAssetInfo['location'] }}
                                        </p>
                                    @endif
                                    @if($selectedAssetInfo['unavailability_reason'])
                                        <p class="mt-2 text-sm font-medium text-yellow-800 bg-yellow-100 px-2 py-1 rounded">
                                            ⚠️ {{ $selectedAssetInfo['unavailability_reason'] }}
                                        </p>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Transaksi <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="movement_date"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    @error('movement_date') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                @if($type === 'peminjaman')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Peminjam <span class="text-red-500">*</span></label>
                    <select wire:model="to_user_id"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        <option value="">Pilih Peminjam</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                        @endforeach
                    </select>
                    @error('to_user_id') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Pengembalian <span class="text-red-500">*</span></label>
                    <input type="date" wire:model="expected_return_date"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    @error('expected_return_date') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                @if($type === 'mutasi')
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Lokasi Tujuan <span class="text-red-500">*</span></label>
                    <select wire:model="to_location_id"
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        <option value="">Pilih Lokasi</option>
                        @foreach($locations as $location)
                            <option value="{{ $location->id }}">{{ $location->name }}</option>
                        @endforeach
                    </select>
                    @error('to_location_id') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
                @endif

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Alasan/Keperluan <span class="text-red-500">*</span></label>
                    <textarea wire:model="reason" rows="2" placeholder="Jelaskan alasan atau keperluan transaksi..."
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors resize-none"></textarea>
                    @error('reason') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="md:col-span-2">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Tambahan</label>
                    <textarea wire:model="notes" rows="2" placeholder="Catatan tambahan (opsional)..."
                        class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors resize-none"></textarea>
                    @error('notes') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                </div>
            </div>

            <div class="mt-8 flex justify-end gap-3">
                <a href="{{ route('movements.index') }}" wire:navigate
                    class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-300">
                    Simpan Transaksi
                </button>
            </div>
        </form>
    </div>
</div>
