<div>
    <!-- Header -->
    <div class="flex items-center gap-4 mb-8">
        <a href="{{ route('stock-opnames.index') }}" wire:navigate
            class="p-2 bg-white border border-gray-300 hover:bg-gray-50 rounded-xl text-gray-600 transition-colors">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $stockOpname->title }}</h1>
            <p class="text-gray-500 mt-1">Scan QR Code aset untuk verifikasi</p>
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

    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-blue-100 rounded-lg">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Total Aset</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAssets }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-green-100 rounded-lg">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Sudah Scan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $scannedCount }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-yellow-100 rounded-lg">
                    <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Belum Scan</p>
                    <p class="text-2xl font-bold text-gray-900">{{ max($totalAssets - $scannedCount, 0) }}</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-5">
            <div class="flex items-center gap-3">
                <div class="p-3 bg-purple-100 rounded-lg">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm text-gray-500">Progress</p>
                    <p class="text-2xl font-bold text-gray-900">{{ $totalAssets > 0 ? round(($scannedCount / $totalAssets) * 100) : 0 }}%</p>
                </div>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Scanner Section (PRD 6.3) -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <!-- Camera Viewfinder -->
            <div class="relative bg-gray-900" wire:ignore>
                <div id="qr-reader" class="w-full aspect-square"></div>
                <div class="absolute inset-0 pointer-events-none">
                    <div class="absolute inset-6 border-2 border-white/60 rounded-lg"></div>
                </div>
            </div>

            <!-- Scan Info (Scanned / Last / Status) -->
            <div class="p-4 bg-gray-50 border-t border-gray-200 space-y-2">
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Scanned:</span>
                    <span class="text-base font-semibold text-gray-900">{{ $scannedCount }} / {{ $totalAssets }}</span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Last:</span>
                    <span class="text-sm font-medium text-gray-900 truncate ml-2">
                        {{ $lastScannedAsset ? $lastScannedAsset->name : '-' }}
                    </span>
                </div>
                <div class="flex items-center justify-between">
                    <span class="text-sm text-gray-600">Status:</span>
                    @if($lastScannedAsset)
                        <span class="inline-flex items-center gap-1 px-2 py-1 bg-green-100 text-green-700 text-sm font-semibold rounded-lg">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Match
                        </span>
                    @else
                        <span class="text-sm text-gray-400">-</span>
                    @endif
                </div>
            </div>

            <!-- Manual Input -->
            <div class="p-4 border-t border-gray-200">
                <div class="flex gap-2">
                    <div class="relative flex-1">
                        <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                        </svg>
                        <input type="text" wire:model="manualCode" wire:keydown.enter="processAsset"
                            placeholder="Ketik kode aset..."
                            class="w-full pl-10 pr-4 py-3 bg-white border border-gray-300 text-gray-900 placeholder-gray-400 rounded-xl focus:border-blue-500 focus:ring-blue-500 transition-colors"
                            autofocus>
                    </div>
                    <button wire:click="processAsset"
                        class="px-4 py-3 bg-blue-600 hover:bg-blue-700 text-white font-semibold rounded-xl shadow-sm transition-all">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Scanned Assets List -->
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Aset Terverifikasi</h3>
            <div class="space-y-3 max-h-96 overflow-y-auto">
                @forelse($scannedAssets as $detail)
                <div class="flex items-center justify-between p-4 bg-gray-50 border border-gray-200 rounded-xl">
                    <div>
                        <p class="font-medium text-gray-900">{{ $detail->asset->code }}</p>
                        <p class="text-sm text-gray-500">{{ $detail->asset->name }}</p>
                    </div>
                    <div class="text-right">
                        @php
                            $conditionColors = [
                                'baik' => 'bg-green-100 text-green-700',
                                'rusak_ringan' => 'bg-yellow-100 text-yellow-700',
                                'rusak_berat' => 'bg-red-100 text-red-700',
                            ];
                        @endphp
                        <span class="px-3 py-1 inline-flex text-xs font-semibold rounded-lg {{ $conditionColors[$detail->actual_condition] ?? 'bg-gray-100 text-gray-700' }}">
                            {{ ucfirst(str_replace('_', ' ', $detail->actual_condition ?? 'N/A')) }}
                        </span>
                        <p class="text-xs text-gray-400 mt-1">{{ $detail->scanned_at?->format('H:i:s') ?? '-' }}</p>
                    </div>
                </div>
                @empty
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                    </svg>
                    <p class="text-gray-500">Belum ada aset yang di-scan</p>
                    <p class="text-sm text-gray-400 mt-1">Mulai scan aset untuk verifikasi</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Complete Button -->
    <div class="mt-6 flex justify-end">
        <button wire:click="completeOpname" wire:confirm="Apakah Anda yakin ingin menyelesaikan stock opname ini? Aset yang belum di-scan akan ditandai sebagai 'Hilang'."
            class="px-6 py-3 bg-green-600 hover:bg-green-700 text-white font-semibold rounded-xl shadow-sm transition-all duration-300">
            <div class="flex items-center gap-2">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                Selesaikan Stock Opname
            </div>
        </button>
    </div>

    <!-- Condition Modal -->
    @if($showConditionModal)
    <div class="fixed inset-0 bg-gray-900/50 backdrop-blur-sm overflow-y-auto h-full w-full z-50 flex items-center justify-center p-4">
        <div class="w-full max-w-md bg-white rounded-2xl border border-gray-200 shadow-xl">
            <div class="p-6">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-xl font-bold text-gray-900">Kondisi Aset</h3>
                    <button wire:click="$set('showConditionModal', false)" class="text-gray-400 hover:text-gray-600 transition-colors">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>

                @if($lastScannedAsset)
                <div class="mb-6 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <div class="space-y-2">
                        <p class="text-sm text-gray-600">Kode: <span class="font-semibold text-gray-900">{{ $lastScannedAsset->code }}</span></p>
                        <p class="text-sm text-gray-600">Nama: <span class="font-semibold text-gray-900">{{ $lastScannedAsset->name }}</span></p>
                    </div>
                </div>
                @endif

                <div class="space-y-3">
                    <button wire:click="saveWithCondition('baik')"
                        class="w-full p-4 bg-green-50 border border-green-200 hover:bg-green-100 text-green-700 rounded-xl transition-colors text-left">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Baik</span>
                        </div>
                    </button>
                    <button wire:click="saveWithCondition('rusak_ringan')"
                        class="w-full p-4 bg-yellow-50 border border-yellow-200 hover:bg-yellow-100 text-yellow-700 rounded-xl transition-colors text-left">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                            </svg>
                            <span class="font-medium">Rusak Ringan</span>
                        </div>
                    </button>
                    <button wire:click="saveWithCondition('rusak_berat')"
                        class="w-full p-4 bg-red-50 border border-red-200 hover:bg-red-100 text-red-700 rounded-xl transition-colors text-left">
                        <div class="flex items-center gap-3">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <span class="font-medium">Rusak Berat</span>
                        </div>
                    </button>
                </div>

                <div class="mt-6">
                    <button wire:click="$set('showConditionModal', false)"
                        class="w-full px-5 py-2.5 bg-white border border-gray-300 hover:bg-gray-50 text-gray-700 font-medium rounded-xl transition-colors">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif
</div>
