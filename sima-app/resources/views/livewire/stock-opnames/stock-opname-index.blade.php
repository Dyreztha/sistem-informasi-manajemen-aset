<div>
    <!-- Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-8">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Stock Opname</h1>
            <p class="text-gray-500 mt-1">Kelola sesi stock opname aset</p>
        </div>
        @can('create-stock-opnames')
        <a href="{{ route('stock-opnames.create') }}" wire:navigate
            class="inline-flex items-center gap-2 px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-300">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            Sesi Baru
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

    <!-- Table -->
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Tanggal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Nama Sesi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Lokasi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Progress</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-right text-xs font-semibold text-gray-700 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($stockOpnames as $opname)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $opname->opname_date->format('d/m/Y') }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">{{ $opname->name }}</div>
                            <div class="text-sm text-gray-500">By: {{ $opname->user->name }}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                            {{ $opname->location?->name ?? 'Semua Lokasi' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center gap-2">
                                <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                    <div class="h-full bg-blue-600 rounded-full" style="width: {{ $opname->progress ?? 0 }}%"></div>
                                </div>
                                <span class="text-sm font-medium text-gray-700">{{ $opname->progress ?? 0 }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @php
                                $statusColors = [
                                    'draft' => 'bg-gray-100 text-gray-700',
                                    'in_progress' => 'bg-blue-100 text-blue-700',
                                    'completed' => 'bg-green-100 text-green-700',
                                    'cancelled' => 'bg-red-100 text-red-700',
                                ];
                            @endphp
                            <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-lg {{ $statusColors[$opname->status] ?? 'bg-gray-100 text-gray-700' }}">
                                {{ ucfirst(str_replace('_', ' ', $opname->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex items-center justify-end gap-2">
                                @if($opname->status === 'in_progress')
                                    @can('update-stock-opnames')
                                    <a href="{{ route('stock-opnames.scan', $opname->id) }}" wire:navigate
                                        class="text-blue-600 hover:text-blue-700 transition-colors font-medium">
                                        Scan
                                    </a>
                                    @endcan
                                @endif
                                @if($opname->status === 'in_progress')
                                    @can('update-stock-opnames')
                                    <button wire:click="complete({{ $opname->id }})" 
                                        class="text-green-600 hover:text-green-700 transition-colors font-medium">
                                        Selesaikan
                                    </button>
                                    @endcan
                                @endif
                                @can('delete-stock-opnames')
                                <button wire:click="delete({{ $opname->id }})" 
                                    wire:confirm="Apakah Anda yakin ingin menghapus sesi ini?"
                                    class="text-red-600 hover:text-red-700 transition-colors font-medium">
                                    Hapus
                                </button>
                                @endcan
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                            </svg>
                            <p class="text-gray-500 text-lg">Tidak ada data stock opname</p>
                            <p class="text-gray-400 text-sm mt-1">Belum ada sesi stock opname</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($stockOpnames->hasPages())
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $stockOpnames->links() }}
        </div>
        @endif
    </div>
</div>
