<div>
    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Manajemen Vendor</h1>
            <p class="text-gray-500 mt-1">Kelola data vendor dan supplier aset</p>
        </div>
        @can('create-vendors')
        <button wire:click="openCreateModal" 
            class="inline-flex items-center gap-2 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Vendor
        </button>
        @endcan
    </div>

    <!-- Alert Messages -->
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

    <!-- Main Card -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <!-- Search Section -->
        <div class="p-6 border-b border-gray-200">
            <div class="relative">
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                    <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </div>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari vendor berdasarkan nama, kode, atau kontak..."
                    class="w-full md:w-96 pl-12 pr-4 py-3 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
            </div>
        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kontak Person</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Telepon</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Email</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jml Aset</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($vendors as $vendor)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 text-sm font-medium bg-blue-100 text-blue-700 rounded-lg">
                                {{ $vendor->code }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="text-sm font-medium text-gray-900">{{ $vendor->name }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $vendor->contact_person ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $vendor->phone ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $vendor->email ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="inline-flex px-3 py-1 text-sm font-medium bg-green-100 text-green-700 rounded-lg">
                                {{ $vendor->assets_count }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-3">
                                @can('edit-vendors')
                                <button wire:click="openEditModal({{ $vendor->id }})" class="text-blue-600 hover:text-blue-700 transition-colors">
                                    Edit
                                </button>
                                @endcan
                                @can('delete-vendors')
                                <button wire:click="confirmDelete({{ $vendor->id }})" class="text-red-600 hover:text-red-700 transition-colors">
                                    Hapus
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <div class="flex flex-col items-center">
                                <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                                <p class="text-gray-500 text-lg">Tidak ada vendor ditemukan.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-6 border-t border-gray-200">
            {{ $vendors->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4" wire:click.self="$set('showModal', false)">
        <div class="relative w-full max-w-lg bg-white rounded-2xl border border-gray-200 shadow-xl">
            <!-- Modal Header -->
            <div class="p-6 border-b border-gray-200">
                <h3 class="text-xl font-bold text-gray-900">
                    {{ $editMode ? 'Edit Vendor' : 'Tambah Vendor Baru' }}
                </h3>
                <p class="text-gray-500 text-sm mt-1">{{ $editMode ? 'Perbarui informasi vendor' : 'Masukkan data vendor baru' }}</p>
            </div>
            
            <!-- Modal Body -->
            <form wire:submit="save">
                <div class="p-6 space-y-5">
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Kode</label>
                            <input type="text" wire:model="code" 
                                class="w-full px-4 py-3 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            @error('code') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                            <input type="text" wire:model="name" 
                                class="w-full px-4 py-3 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            @error('name') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kontak Person</label>
                        <input type="text" wire:model="contact_person" 
                            class="w-full px-4 py-3 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                        @error('contact_person') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Telepon</label>
                            <input type="text" wire:model="phone" 
                                class="w-full px-4 py-3 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            @error('phone') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                            <input type="email" wire:model="email" 
                                class="w-full px-4 py-3 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            @error('email') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Alamat</label>
                        <textarea wire:model="address" rows="2"
                            class="w-full px-4 py-3 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors resize-none"></textarea>
                        @error('address') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea wire:model="description" rows="2"
                            class="w-full px-4 py-3 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors resize-none"></textarea>
                        @error('description') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                
                <!-- Modal Footer -->
                <div class="p-6 border-t border-gray-200 flex justify-end gap-3">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-6 py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-300">
                        {{ $editMode ? 'Perbarui' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="relative w-full max-w-sm bg-white rounded-2xl border border-gray-200 shadow-xl">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100 mb-5">
                    <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Vendor?</h3>
                <p class="text-gray-500">
                    Apakah Anda yakin ingin menghapus vendor ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="mt-8 flex justify-center gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="px-6 py-3 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button wire:click="delete"
                        class="px-6 py-3 bg-red-600 hover:bg-red-700 text-white font-semibold rounded-xl transition-all duration-300">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
