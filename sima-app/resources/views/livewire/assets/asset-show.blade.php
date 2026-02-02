<div>
    <x-slot name="header">
        Detail Aset: {{ $asset->code }}
    </x-slot>
    
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div class="flex items-center">
            <a href="{{ route('assets.index') }}" wire:navigate class="mr-4 p-2 rounded-lg text-gray-500 hover:text-gray-900 hover:bg-gray-100 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div>
                <h2 class="text-2xl font-bold text-gray-900">{{ $asset->name }}</h2>
                <p class="text-gray-600">{{ $asset->code }}</p>
            </div>
        </div>
        @can('edit-assets')
        <a href="{{ route('assets.edit', $asset) }}" wire:navigate 
           class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2.5 bg-amber-500 hover:bg-amber-600 rounded-xl font-semibold text-sm text-white shadow-sm transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
            </svg>
            Edit
        </a>
        @endcan
    </div>
    
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Left Column - Main Info -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Informasi Dasar -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Informasi Dasar</h3>
                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <label class="text-sm text-gray-500">Kode Aset</label>
                        <p class="font-semibold text-blue-600 mt-1">{{ $asset->code }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Nama Aset</label>
                        <p class="font-semibold text-gray-900 mt-1">{{ $asset->name }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Kategori</label>
                        <p class="text-gray-600 mt-1">{{ $asset->category->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Lokasi</label>
                        <p class="text-gray-600 mt-1">{{ $asset->location->name ?? '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Merk</label>
                        <p class="text-gray-600 mt-1">{{ $asset->brand ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Model/Tipe</label>
                        <p class="text-gray-600 mt-1">{{ $asset->model ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Nomor Seri</label>
                        <p class="text-gray-600 mt-1">{{ $asset->serial_number ?: '-' }}</p>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Vendor</label>
                        <p class="text-gray-600 mt-1">{{ $asset->vendor->name ?? '-' }}</p>
                    </div>
                </div>
            </div>
                    
            <!-- Informasi Keuangan -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Informasi Keuangan</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-blue-50 border border-blue-100 p-4 rounded-xl">
                        <label class="text-sm text-blue-600">Harga Perolehan</label>
                        <p class="text-lg font-bold text-blue-700 mt-1">Rp {{ number_format($asset->purchase_price, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-green-50 border border-green-100 p-4 rounded-xl">
                        <label class="text-sm text-green-600">Nilai Saat Ini</label>
                        <p class="text-lg font-bold text-green-700 mt-1">Rp {{ number_format($asset->current_value, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-red-50 border border-red-100 p-4 rounded-xl">
                        <label class="text-sm text-red-600">Penyusutan</label>
                        <p class="text-lg font-bold text-red-700 mt-1">Rp {{ number_format($asset->depreciation_value, 0, ',', '.') }}</p>
                    </div>
                    <div class="bg-gray-50 p-4 rounded-xl">
                        <label class="text-sm text-gray-500">Tanggal Beli</label>
                        <p class="font-semibold text-gray-900 mt-1">{{ $asset->purchase_date ? $asset->purchase_date->format('d/m/Y') : '-' }}</p>
                    </div>
                </div>
                @if($asset->warranty_end_date)
                <div class="mt-4 p-3 {{ $asset->warranty_end_date->isPast() ? 'bg-red-50 border border-red-100' : 'bg-green-50 border border-green-100' }} rounded-xl">
                    <span class="text-sm {{ $asset->warranty_end_date->isPast() ? 'text-red-700' : 'text-green-700' }}">
                        Garansi {{ $asset->warranty_end_date->isPast() ? 'berakhir' : 'sampai' }}: {{ $asset->warranty_end_date->format('d/m/Y') }}
                    </span>
                </div>
                @endif
            </div>
                    
            <!-- Status & Kondisi -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Status & Kondisi</h3>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div>
                        <label class="text-sm text-gray-500">Status</label>
                        @php
                            $statusColors = [
                                'tersedia' => 'bg-green-100 text-green-700',
                                'digunakan' => 'bg-blue-100 text-blue-700',
                                'maintenance' => 'bg-amber-100 text-amber-700',
                                'disposal' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="mt-2 inline-block px-3 py-1.5 rounded-lg text-sm font-semibold {{ $statusColors[$asset->status] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ ucfirst($asset->status) }}
                        </span>
                    </div>
                    <div>
                        <label class="text-sm text-gray-500">Kondisi</label>
                        @php
                            $conditionColors = [
                                'baik' => 'bg-green-100 text-green-700',
                                'rusak_ringan' => 'bg-amber-100 text-amber-700',
                                'rusak_berat' => 'bg-red-100 text-red-700',
                                'hilang' => 'bg-gray-100 text-gray-600',
                            ];
                            $conditionLabels = [
                                'baik' => 'Baik',
                                'rusak_ringan' => 'Rusak Ringan',
                                'rusak_berat' => 'Rusak Berat',
                                'hilang' => 'Hilang',
                            ];
                        @endphp
                        <span class="mt-2 inline-block px-3 py-1.5 rounded-lg text-sm font-semibold {{ $conditionColors[$asset->condition] ?? 'bg-gray-100 text-gray-600' }}">
                            {{ $conditionLabels[$asset->condition] ?? $asset->condition }}
                        </span>
                    </div>
                    <div class="col-span-2">
                        <label class="text-sm text-gray-500">Penanggung Jawab</label>
                        <p class="font-semibold text-gray-900 mt-1">{{ $asset->assignedUser->name ?? 'Belum ditugaskan' }}</p>
                        @if($asset->assigned_date)
                        <p class="text-xs text-gray-500 mt-0.5">Sejak: {{ $asset->assigned_date->format('d/m/Y') }}</p>
                        @endif
                    </div>
                </div>
            </div>
                    
            <!-- Deskripsi & Catatan -->
            @if($asset->description || $asset->notes)
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Keterangan</h3>
                @if($asset->description)
                <div class="mb-4">
                    <label class="text-sm text-gray-500">Deskripsi</label>
                    <p class="mt-1 text-gray-600">{{ $asset->description }}</p>
                </div>
                @endif
                @if($asset->notes)
                <div>
                    <label class="text-sm text-gray-500">Catatan</label>
                    <p class="mt-1 text-gray-600">{{ $asset->notes }}</p>
                </div>
                @endif
            </div>
            @endif
                    
            <!-- Riwayat Mutasi -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Riwayat Pergerakan</h3>
                @if($asset->movements->count() > 0)
                <div class="space-y-3">
                    @foreach($asset->movements->take(5) as $movement)
                    <div class="flex items-center p-3 bg-gray-50 rounded-xl">
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ ucfirst($movement->type) }}</p>
                            <p class="text-xs text-gray-500">{{ $movement->movement_date->format('d/m/Y') }} - {{ $movement->reason ?? 'Tidak ada keterangan' }}</p>
                        </div>
                        <span class="px-2.5 py-1 text-xs rounded-lg font-semibold {{ $movement->status === 'approved' ? 'bg-green-100 text-green-700' : ($movement->status === 'rejected' ? 'bg-red-100 text-red-700' : 'bg-amber-100 text-amber-700') }}">
                            {{ ucfirst($movement->status) }}
                        </span>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">Belum ada riwayat pergerakan</p>
                @endif
            </div>
        </div>
                
        <!-- Right Column - QR Code & Documents -->
        <div class="space-y-6">
            <!-- QR Code -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200 text-center">QR Code</h3>
                <div class="flex flex-col items-center">
                    <div class="bg-white p-4 rounded-xl border border-gray-100">
                        <img src="data:image/svg+xml;base64,{{ $this->qrCode }}" alt="QR Code {{ $asset->code }}" class="w-48 h-48">
                    </div>
                    <p class="mt-3 text-lg font-bold text-gray-900">{{ $asset->code }}</p>
                    <p class="text-sm text-gray-500">{{ $asset->name }}</p>
                    <button onclick="window.print()" class="mt-4 inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-xl font-semibold text-sm text-white shadow-sm transition-all">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                        </svg>
                        Cetak Label
                    </button>
                </div>
            </div>
                    
            <!-- Documents -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Dokumen</h3>
                @if($asset->documents->count() > 0)
                <div class="space-y-2">
                    @foreach($asset->documents as $doc)
                    <a href="{{ Storage::url($doc->file_path) }}" target="_blank" class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                        <svg class="w-8 h-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                        </svg>
                        <div class="flex-1">
                            <p class="text-sm font-medium text-gray-900">{{ $doc->title }}</p>
                            <p class="text-xs text-gray-500">{{ $doc->getFileSizeFormatted() }}</p>
                        </div>
                    </a>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">Tidak ada dokumen</p>
                @endif
            </div>
                    
            <!-- Maintenance History -->
            <div class="bg-white rounded-2xl border border-gray-200 p-6 shadow-sm">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 pb-3 border-b border-gray-200">Riwayat Pemeliharaan</h3>
                @if($asset->maintenances->count() > 0)
                <div class="space-y-2">
                    @foreach($asset->maintenances->take(3) as $maintenance)
                    <div class="p-3 bg-gray-50 rounded-xl">
                        <div class="flex justify-between items-start">
                            <p class="text-sm font-medium text-gray-900">{{ $maintenance->ticket_number }}</p>
                            <span class="px-2.5 py-1 text-xs rounded-lg font-semibold {{ $maintenance->status === 'completed' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }}">
                                {{ ucfirst($maintenance->status) }}
                            </span>
                        </div>
                        <p class="text-xs text-gray-500 mt-1">{{ Str::limit($maintenance->description, 50) }}</p>
                        <p class="text-xs text-gray-500 mt-1">Biaya: Rp {{ number_format($maintenance->actual_cost ?? $maintenance->estimated_cost ?? 0, 0, ',', '.') }}</p>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 text-center py-4">Tidak ada riwayat pemeliharaan</p>
                @endif
            </div>
        </div>
    </div>
</div>
