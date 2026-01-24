<div>
    <!-- Header -->
    <div class="flex justify-between items-center mb-8">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Manajemen Kategori</h2>
            <p class="text-sm text-gray-500 mt-1">Kelola kategori aset dan pengaturan depresiasi</p>
        </div>
        @can('create-categories')
        <button wire:click="openCreateModal" 
            class="inline-flex items-center px-4 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-sm transition-all duration-300">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tambah Kategori
        </button>
        @endcan
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-50 border border-red-200 text-red-700 rounded-xl">
            {{ session('error') }}
        </div>
    @endif

    <!-- Search -->
    <div class="mb-6">
        <div class="relative w-full md:w-1/3">
            <span class="absolute inset-y-0 left-0 pl-4 flex items-center text-gray-400">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
            </span>
            <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari kategori..."
                class="w-full pl-12 bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500">
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Kode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Depresiasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Masa Pakai</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Jumlah Aset</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($categories as $category)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 bg-blue-100 text-blue-700 rounded-lg text-sm font-medium">{{ $category->code }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            {{ $category->name }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $category->depreciation_rate }}% / {{ $category->depreciation_method == 'straight_line' ? 'Garis Lurus' : 'Saldo Menurun' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $category->useful_life_years }} tahun
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-1 bg-gray-100 text-gray-700 rounded-lg text-sm font-medium">{{ $category->assets_count }}</span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                            @can('edit-categories')
                            <button wire:click="openEditModal({{ $category->id }})" class="text-blue-600 hover:text-blue-700 mr-3 font-medium transition-colors">
                                Edit
                            </button>
                            @endcan
                            @can('delete-categories')
                            <button wire:click="confirmDelete({{ $category->id }})" class="text-red-600 hover:text-red-700 font-medium transition-colors">
                                Hapus
                            </button>
                            @endcan
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">
                            <svg class="w-12 h-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21h10a2 2 0 002-2V9.414a1 1 0 00-.293-.707l-5.414-5.414A1 1 0 0012.586 3H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                            </svg>
                            Tidak ada kategori ditemukan.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="px-6 py-4 border-t border-gray-200">
            {{ $categories->links() }}
        </div>
    </div>

    <!-- Modal Form -->
    @if($showModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50" wire:click.self="$set('showModal', false)">
        <div class="relative top-20 mx-auto p-6 border border-gray-200 w-full max-w-md shadow-xl rounded-2xl bg-white">
            <h3 class="text-xl font-bold text-gray-900 mb-6">
                {{ $editMode ? 'Edit Kategori' : 'Tambah Kategori Baru' }}
            </h3>
            <form wire:submit="save">
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kode</label>
                        <input type="text" wire:model="code" 
                            class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                        @error('code') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nama</label>
                        <input type="text" wire:model="name" 
                            class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                        @error('name') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                        <textarea wire:model="description" rows="2"
                            class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500"></textarea>
                        @error('description') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Rate Depresiasi (%)</label>
                            <input type="number" wire:model="depreciation_rate" step="0.01" min="0" max="100"
                                class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            @error('depreciation_rate') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Masa Pakai (Tahun)</label>
                            <input type="number" wire:model="useful_life_years" min="1"
                                class="w-full bg-white border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            @error('useful_life_years') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Metode Depresiasi</label>
                        <select wire:model="depreciation_method"
                            class="w-full bg-white border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500">
                            <option value="straight_line">Garis Lurus (Straight Line)</option>
                            <option value="declining_balance">Saldo Menurun (Declining Balance)</option>
                        </select>
                        @error('depreciation_method') <span class="text-red-600 text-sm mt-1 block">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" wire:click="$set('showModal', false)"
                        class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button type="submit"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-semibold shadow-sm transition-all duration-300">
                        {{ $editMode ? 'Perbarui' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
    @endif

    <!-- Delete Confirmation Modal -->
    @if($showDeleteModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-6 border border-gray-200 w-full max-w-sm shadow-xl rounded-2xl bg-white">
            <div class="text-center">
                <div class="mx-auto flex items-center justify-center h-14 w-14 rounded-full bg-red-100">
                    <svg class="h-7 w-7 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mt-4">Hapus Kategori?</h3>
                <p class="text-sm text-gray-500 mt-2">
                    Apakah Anda yakin ingin menghapus kategori ini? Tindakan ini tidak dapat dibatalkan.
                </p>
                <div class="mt-6 flex justify-center gap-3">
                    <button wire:click="$set('showDeleteModal', false)"
                        class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 rounded-xl font-medium transition-colors">
                        Batal
                    </button>
                    <button wire:click="delete"
                        class="px-5 py-2.5 bg-red-600 hover:bg-red-700 text-white rounded-xl font-semibold transition-colors">
                        Hapus
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
