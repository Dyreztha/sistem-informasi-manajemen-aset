<div>
    <x-slot name="header">
        Daftar Aset
    </x-slot>
    
    <!-- Header Actions -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-6">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manajemen Aset</h2>
            <p class="text-gray-600 mt-1">Kelola semua aset perusahaan</p>
        </div>
        @can('create-assets')
        <a href="{{ route('assets.create') }}" wire:navigate 
           class="mt-4 sm:mt-0 inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 rounded-xl font-semibold text-sm text-white shadow-sm transition-all">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Aset
        </a>
        @endcan
    </div>
    
    <!-- Flash Message -->
    @if (session()->has('message'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl flex items-center" role="alert">
            <svg class="w-5 h-5 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('message') }}
        </div>
    @endif
    
    <div class="bg-white rounded-2xl border border-gray-200 overflow-hidden shadow-sm">
        <div class="p-6">
            <!-- Filters -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
                <!-- Search -->
                <div class="lg:col-span-2 relative">
                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                        <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                    </div>
                    <input wire:model.live.debounce.300ms="search" type="text" placeholder="Cari nama, kode, atau serial number..." 
                        class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                </div>
                
                <!-- Category Filter -->
                <select wire:model.live="categoryFilter" class="py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Kategori</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                    @endforeach
                </select>
                
                <!-- Status Filter -->
                <select wire:model.live="statusFilter" class="py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Status</option>
                    <option value="tersedia">Tersedia</option>
                    <option value="digunakan">Digunakan</option>
                    <option value="maintenance">Maintenance</option>
                    <option value="disposal">Disposal</option>
                </select>
                
                <!-- Condition Filter -->
                <select wire:model.live="conditionFilter" class="py-2.5 bg-white border border-gray-300 rounded-xl text-gray-900 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                    <option value="">Semua Kondisi</option>
                    <option value="baik">Baik</option>
                    <option value="rusak_ringan">Rusak Ringan</option>
                    <option value="rusak_berat">Rusak Berat</option>
                    <option value="hilang">Hilang</option>
                </select>
            </div>
            
            <!-- Reset Filters -->
            @if($search || $categoryFilter || $statusFilter || $conditionFilter || $locationFilter)
                <div class="mb-4">
                    <button wire:click="resetFilters" class="text-sm text-blue-600 hover:text-blue-700 flex items-center">
                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/>
                        </svg>
                        Reset Filter
                    </button>
                </div>
            @endif
            
            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-200 bg-gray-50">
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:text-gray-900" wire:click="sortBy('code')">
                                <div class="flex items-center space-x-1">
                                    <span>Kode</span>
                                    @if($sortField === 'code')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:text-gray-900" wire:click="sortBy('name')">
                                <div class="flex items-center space-x-1">
                                    <span>Nama Aset</span>
                                    @if($sortField === 'name')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kategori</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kondisi</th>
                            <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider cursor-pointer hover:text-gray-900" wire:click="sortBy('current_value')">
                                <div class="flex items-center space-x-1">
                                    <span>Nilai</span>
                                    @if($sortField === 'current_value')
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $sortDirection === 'asc' ? 'M5 15l7-7 7 7' : 'M19 9l-7 7-7-7' }}"/>
                                        </svg>
                                    @endif
                                </div>
                            </th>
                            <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white">
                                @forelse($assets as $asset)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <span class="text-sm font-medium text-blue-600">{{ $asset->code }}</span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $asset->name }}</div>
                                            <div class="text-sm text-gray-500">{{ $asset->brand }} {{ $asset->model }}</div>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $asset->category->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                            {{ $asset->location->name ?? '-' }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
                                            @php
                                                $statusColors = [
                                                    'tersedia' => 'bg-green-100 text-green-700',
                                                    'digunakan' => 'bg-blue-100 text-blue-700',
                                                    'maintenance' => 'bg-amber-100 text-amber-700',
                                                    'disposal' => 'bg-red-100 text-red-700',
                                                ];
                                            @endphp
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $statusColors[$asset->status] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ ucfirst($asset->status) }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap">
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
                                            <span class="px-2.5 py-1 text-xs font-semibold rounded-lg {{ $conditionColors[$asset->condition] ?? 'bg-gray-100 text-gray-600' }}">
                                                {{ $conditionLabels[$asset->condition] ?? $asset->condition }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-600">
                                            Rp {{ number_format($asset->current_value, 0, ',', '.') }}
                                        </td>
                                        <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                                            <div class="flex justify-end space-x-1">
                                                <a href="{{ route('assets.show', $asset) }}" wire:navigate 
                                                   class="p-2 rounded-lg text-gray-500 hover:text-blue-600 hover:bg-gray-100 transition-colors" title="Lihat">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                                    </svg>
                                                </a>
                                                @can('edit-assets')
                                                <a href="{{ route('assets.edit', $asset) }}" wire:navigate 
                                                   class="p-2 rounded-lg text-gray-500 hover:text-amber-600 hover:bg-gray-100 transition-colors" title="Edit">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                    </svg>
                                                </a>
                                                @endcan
                                                @can('delete-assets')
                                                <button wire:click="confirmDelete({{ $asset->id }})" 
                                                        class="p-2 rounded-lg text-gray-500 hover:text-red-600 hover:bg-gray-100 transition-colors" title="Hapus">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                                @endcan
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="8" class="px-4 py-12 text-center">
                                            <div class="flex flex-col items-center">
                                                <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                                                </svg>
                                                <p class="text-gray-500">Tidak ada data aset</p>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    
                    <!-- Pagination -->
                    <div class="mt-6">
                        {{ $assets->links() }}
                    </div>
                </div>
            </div>
    
    <!-- Delete Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-center justify-center min-h-screen px-4 pt-4 pb-20 text-center sm:p-0">
            <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm transition-opacity" wire:click="$set('showDeleteModal', false)"></div>
            <div class="relative inline-block w-full max-w-md p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white border border-gray-200 shadow-xl rounded-2xl">
                <div class="flex items-center space-x-4">
                    <div class="flex-shrink-0 w-12 h-12 rounded-full bg-red-100 flex items-center justify-center">
                        <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-lg font-semibold text-gray-900" id="modal-title">Hapus Aset</h3>
                        <p class="text-sm text-gray-600 mt-1">Apakah Anda yakin ingin menghapus aset ini? Data yang dihapus tidak dapat dikembalikan.</p>
                    </div>
                </div>
                <div class="mt-6 flex justify-end space-x-3">
                    <button wire:click="$set('showDeleteModal', false)" type="button" 
                            class="px-4 py-2 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button wire:click="deleteAsset" type="button" 
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-xl font-medium transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
