<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Sirkulasi Aset</h1>
            <p class="text-gray-500 mt-1">Kelola peminjaman, mutasi, dan pengembalian aset</p>
        </div>
        @can('create-movements')
        <a href="{{ route('movements.create') }}" wire:navigate
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Transaksi Baru
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
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
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
                <option value="peminjaman">Peminjaman</option>
                <option value="mutasi">Mutasi</option>
                <option value="pengembalian">Pengembalian</option>
            </select>
            <select wire:model.live="filterStatus"
                class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                <option value="">Semua Status</option>
                <option value="pending">Pending</option>
                <option value="active">Active</option>
                <option value="returned">Returned</option>
                <option value="rejected">Rejected</option>
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
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Dari</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Ke</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($movements as $movement)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $movement->movement_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $movement->asset->code }}</div>
                            <div class="text-sm text-gray-500">{{ $movement->asset->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $typeColors = [
                                    'peminjaman' => 'bg-blue-100 text-blue-700',
                                    'mutasi' => 'bg-purple-100 text-purple-700',
                                    'pengembalian' => 'bg-green-100 text-green-700',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-lg {{ $typeColors[$movement->type] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst($movement->type) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="text-gray-600">{{ $movement->fromUser?->name ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $movement->fromLocation?->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm">
                            <div class="text-gray-600">{{ $movement->toUser?->name ?? '-' }}</div>
                            <div class="text-xs text-gray-500">{{ $movement->toLocation?->name ?? '-' }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $isReturned = $movement->type === 'peminjaman' && $movement->actual_return_date;
                                $displayStatus = match(true) {
                                    $movement->status === 'pending' => 'Pending',
                                    $movement->status === 'rejected' => 'Ditolak',
                                    $isReturned => 'Dikembalikan',
                                    $movement->status === 'approved' && $movement->type === 'peminjaman' => 'Disetujui',
                                    $movement->status === 'approved' && $movement->type === 'mutasi' => 'Selesai',
                                    $movement->status === 'approved' && $movement->type === 'pengembalian' => 'Selesai',
                                    default => ucfirst($movement->status),
                                };
                                $statusClass = match(true) {
                                    $movement->status === 'pending' => 'bg-yellow-100 text-yellow-700',
                                    $movement->status === 'rejected' => 'bg-red-100 text-red-700',
                                    $isReturned => 'bg-blue-100 text-blue-700',
                                    $movement->status === 'approved' => 'bg-green-100 text-green-700',
                                    default => 'bg-gray-100 text-gray-700',
                                };
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-lg {{ $statusClass }}">
                                {{ $displayStatus }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                @if($movement->status === 'pending')
                                    <button wire:click="approveMovement({{ $movement->id }})" 
                                        class="px-3 py-1.5 bg-green-100 text-green-700 hover:bg-green-200 rounded-lg transition-colors font-medium text-xs">
                                        ✓ Approve
                                    </button>
                                    <button wire:click="rejectMovement({{ $movement->id }})" 
                                        class="px-3 py-1.5 bg-red-100 text-red-700 hover:bg-red-200 rounded-lg transition-colors font-medium text-xs">
                                        ✗ Reject
                                    </button>
                                @endif
                                
                                @if($movement->type === 'peminjaman' && $movement->status === 'approved' && !$movement->actual_return_date)
                                    <button wire:click="openReturnModal({{ $movement->id }})" 
                                        class="px-3 py-1.5 bg-blue-100 text-blue-700 hover:bg-blue-200 rounded-lg transition-colors font-medium text-xs">
                                        Kembalikan
                                    </button>
                                @endif

                                @if($movement->status === 'approved' && $movement->type === 'peminjaman' && !$movement->actual_return_date)
                                    <span class="text-xs text-gray-500">Dipinjam</span>
                                @elseif($movement->type === 'peminjaman' && $movement->actual_return_date)
                                    <span class="text-xs text-gray-500">Dikembalikan</span>
                                @elseif($movement->status === 'approved' && $movement->type !== 'peminjaman')
                                    <span class="text-xs text-gray-500">Selesai</span>
                                @elseif($movement->status === 'rejected')
                                    <span class="text-xs text-gray-500">Ditolak</span>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                            </svg>
                            <p class="text-gray-500 text-lg">Tidak ada data sirkulasi</p>
                            <p class="text-gray-400 text-sm mt-1">Belum ada transaksi pergerakan aset</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($movements->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $movements->links() }}
        </div>
        @endif
    </div>

    <!-- Return Modal -->
    @if($showReturnModal && $selectedMovement)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 shadow-xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Pengembalian Aset</h3>
                    <button wire:click="$set('showReturnModal', false)" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">Aset: <span class="font-semibold text-gray-900">{{ $selectedMovement->asset->name }}</span></p>
                        <p class="text-sm text-gray-600">Kode: <span class="font-semibold text-gray-900">{{ $selectedMovement->asset->code }}</span></p>
                        <p class="text-sm text-gray-600">Peminjam: <span class="font-semibold text-gray-900">{{ $selectedMovement->toUser?->name }}</span></p>
                    </div>
                </div>

                <div class="space-y-5">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kondisi Saat Dikembalikan</label>
                        <select wire:model="returnCondition"
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors">
                            <option value="baik">Baik</option>
                            <option value="rusak_ringan">Rusak Ringan</option>
                            <option value="rusak_berat">Rusak Berat</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Catatan Pengembalian</label>
                        <textarea wire:model="returnNotes" rows="3" placeholder="Tambahkan catatan pengembalian..."
                            class="w-full px-4 py-2.5 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors resize-none"></textarea>
                    </div>
                </div>

                <div class="mt-8 flex justify-end gap-3">
                    <button wire:click="$set('showReturnModal', false)"
                        class="px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                    <button wire:click="processReturn"
                        class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-300">
                        Proses Pengembalian
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
