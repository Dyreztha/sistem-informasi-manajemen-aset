<div class="space-y-6">
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manajemen Lokasi</h2>
            <p class="text-gray-500 mt-1">Kelola lokasi dan penempatan aset</p>
        </div>
        @can('create-locations')
        <button wire:click="openCreateModal" 
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition-all duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Lokasi
        </button>
        @endcan
    </div>

    <!-- Alerts -->
    @if (session()->has('message'))
        <div class="p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Search -->
        <div class="p-5 border-b border-gray-200">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari lokasi..."
                    class="w-full md:w-80 pl-11 pr-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Gedung</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lantai</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ruangan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Parent</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jml Aset</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($locations as $location)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 text-sm font-medium bg-blue-100 text-blue-700 rounded-lg">
                                {{ $location->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $location->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $location->building ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $location->floor ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $location->room ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $location->parent?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-2.5 py-1 text-sm font-medium bg-green-100 text-green-700 rounded-lg">
                                {{ $location->assets_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                @can('edit-locations')
                                <button wire:click="openEditModal({{ $location->id }})" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 text-sm rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                    </svg>
                                    Edit
                                </button>
                                @endcan
                                @can('delete-locations')
                                <button wire:click="confirmDelete({{ $location->id }})" 
                                    class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-red-600 hover:bg-red-700 text-white text-sm rounded-xl transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                    Hapus
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <p class="text-gray-500">Tidak ada lokasi ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-5 border-t border-gray-200">
            {{ $locations->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-start justify-center pt-20" wire:click.self="$set('showModal', false)">
        <div class="relative w-full max-w-lg mx-4 bg-white rounded-2xl border border-gray-200 shadow-xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">
                        {{ $editMode ? 'Edit Lokasi' : 'Tambah Lokasi Baru' }}
                    </h3>
                    <button wire:click="$set('showModal', false)" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-100 rounded-xl transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <form wire:submit="save">
                    <div class="space-y-5">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Kode</label>
                                <input type="text" wire:model="code" 
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('code') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                                <input type="text" wire:model="name" 
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('name') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Parent Lokasi</label>
                            <select wire:model="parent_id"
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                <option value="">-- Tidak Ada --</option>
                                @foreach($parentLocations as $parent)
                                    <option value="{{ $parent->id }}">{{ $parent->name }}</option>
                                @endforeach
                            </select>
                            @error('parent_id') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Gedung</label>
                            <input type="text" wire:model="building" 
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            @error('building') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Lantai</label>
                                <input type="text" wire:model="floor" 
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('floor') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Ruangan</label>
                                <input type="text" wire:model="room" 
                                    class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                                @error('room') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                            <textarea wire:model="description" rows="2"
                                class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors resize-none"></textarea>
                            @error('description') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="mt-8 flex justify-end gap-3">
                        <button type="button" wire:click="$set('showModal', false)"
                            class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-colors">
                            Batal
                        </button>
                        <button type="submit"
                            class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-xl shadow-sm transition-all duration-200">
                            {{ $editMode ? 'Perbarui' : 'Simpan' }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-start justify-center pt-20">
        <div class="relative w-full max-w-sm mx-4 bg-white rounded-2xl border border-gray-200 shadow-xl">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-5">
                    <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Lokasi?</h3>
                <p class="text-gray-500">
                    Apakah Anda yakin ingin menghapus lokasi ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="mt-8 flex justify-center gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button wire:click="delete"
                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white font-medium rounded-xl transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
