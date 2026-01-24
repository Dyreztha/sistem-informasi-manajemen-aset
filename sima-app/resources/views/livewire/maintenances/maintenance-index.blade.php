<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Pemeliharaan Aset</h1>
            <p class="text-gray-500 mt-1">Kelola jadwal dan riwayat pemeliharaan aset</p>
        </div>
        @can('create-maintenances')
        <a href="{{ route('maintenances.create') }}" wire:navigate
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Tiket Baru
        </a>
        @endcan
    </div>

    @if (session()->has('message'))
        <div class="mb-6 p-4 bg-green-50 border border-green-200 text-green-700 rounded-xl flex items-center gap-3">
            <svg class="w-5 h-5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('message') }}
        </div>
    @endif

    <!-- Filters -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="relative">
                <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                </svg>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="Cari aset..."
                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
            </div>
            <select wire:model.live="filterType"
                class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                <option value="">Semua Tipe</option>
                <option value="preventive">Preventive</option>
                <option value="corrective">Corrective</option>
                <option value="predictive">Predictive</option>
            </select>
            <select wire:model.live="filterPriority"
                class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                <option value="">Semua Prioritas</option>
                <option value="low">Low</option>
                <option value="medium">Medium</option>
                <option value="high">High</option>
                <option value="critical">Critical</option>
            </select>
            <select wire:model.live="filterStatus"
                class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="in_progress">In Progress</option>
                <option value="completed">Completed</option>
                <option value="cancelled">Cancelled</option>
            </select>
        </div>
    </div>

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Aset</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tipe</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Prioritas</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Vendor</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($maintenances as $maintenance)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $maintenance->scheduled_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $maintenance->asset->code }}</div>
                            <div class="text-sm text-gray-500">{{ $maintenance->asset->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    'preventive' => 'bg-blue-100 text-blue-700',
                                    'corrective' => 'bg-orange-100 text-orange-700',
                                    'predictive' => 'bg-purple-100 text-purple-700',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-lg {{ $typeColors[$maintenance->type] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($maintenance->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $priorityColors = [
                                    'low' => 'bg-green-100 text-green-700',
                                    'medium' => 'bg-yellow-100 text-yellow-700',
                                    'high' => 'bg-orange-100 text-orange-700',
                                    'critical' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-lg {{ $priorityColors[$maintenance->priority] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($maintenance->priority) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $maintenance->vendor?->name ?? '-' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-lg {{ $statusColors[$maintenance->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $maintenance->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                @can('update-maintenances')
                                <button wire:click="openUpdateModal({{ $maintenance->id }})" 
                                    class="text-blue-600 hover:text-blue-700 transition-colors font-medium">
                                    Update
                                </button>
                                @endcan
                                @can('delete-maintenances')
                                <button wire:click="delete({{ $maintenance->id }})" 
                                    wire:confirm="Apakah Anda yakin ingin menghapus tiket ini?"
                                    class="text-red-600 hover:text-red-700 transition-colors font-medium">
                                    Hapus
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <p class="text-gray-500 text-lg">Tidak ada data pemeliharaan</p>
                            <p class="text-gray-400 text-sm mt-1">Belum ada tiket pemeliharaan</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($maintenances->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $maintenances->links() }}
        </div>
        @endif
    </div>

    <!-- Update Modal -->
    @if($showUpdateModal && $selectedMaintenance)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 shadow-xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Update Status</h3>
                    <button wire:click="$set('showUpdateModal', false)" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">Aset: <span class="font-semibold text-gray-900">{{ $selectedMaintenance->asset->name }}</span></p>
                        <p class="text-sm text-gray-600">Tipe: <span class="font-semibold text-gray-900">{{ ucfirst($selectedMaintenance->type) }}</span></p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status Baru</label>
                        <select wire:model="updateStatus"
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <option value="pending">Pending</option>
                            <option value="in_progress">In Progress</option>
                            <option value="completed">Completed</option>
                            <option value="cancelled">Cancelled</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Biaya</label>
                        <input type="number" wire:model="updateCost" placeholder="0"
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan</label>
                        <textarea wire:model="updateNotes" rows="3" placeholder="Tambahkan catatan..."
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors resize-none"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button wire:click="$set('showUpdateModal', false)"
                        class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button wire:click="updateMaintenance"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-300">
                        Update
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
