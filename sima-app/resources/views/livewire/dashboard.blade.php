<div>
    <!-- Welcome Section -->
    <div class="mb-8">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang, {{ auth()->user()->name }}!</h1>
        <p class="text-gray-600">Berikut adalah ringkasan data aset anda hari ini.</p>
    </div>
    
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Aset -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-blue-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Total Aset</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ number_format($totalAssets) }}</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-xl">
                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-gray-500 text-sm">
                <svg class="w-4 h-4 mr-1 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"/>
                </svg>
                Aktif & Terdaftar
            </div>
        </div>
        
        <!-- Total Nilai Perolehan -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-emerald-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Nilai Perolehan</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($totalValue / 1000000, 1, ',', '.') }}M</p>
                </div>
                <div class="p-3 bg-emerald-100 rounded-xl">
                    <svg class="h-8 w-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-gray-500 text-sm">
                <span class="truncate">Total Investasi Aset</span>
            </div>
        </div>
        
        <!-- Nilai Saat Ini -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-amber-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Nilai Saat Ini</p>
                    <p class="text-2xl font-bold text-gray-900 mt-2">Rp {{ number_format($currentValue / 1000000, 1, ',', '.') }}M</p>
                </div>
                <div class="p-3 bg-amber-100 rounded-xl">
                    <svg class="h-8 w-8 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-gray-500 text-sm">
                Setelah Penyusutan
            </div>
        </div>
        
        <!-- Maintenance Alert -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 border-l-4 border-l-rose-500 p-6">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-gray-500 text-sm font-medium">Maintenance Alert</p>
                    <p class="text-3xl font-bold text-gray-900 mt-2">{{ count($maintenanceAlerts) }}</p>
                </div>
                <div class="p-3 bg-rose-100 rounded-xl">
                    <svg class="h-8 w-8 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                </div>
            </div>
            <div class="mt-4 flex items-center text-gray-500 text-sm">
                Perlu Perhatian
            </div>
        </div>
    </div>
    
    <!-- Kondisi Aset Chart -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Kondisi Aset -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Kondisi Aset</h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Overview</span>
            </div>
            <div class="space-y-5">
                @php
                    $total = array_sum($assetsCondition) ?: 1;
                    $colors = [
                        'baik' => ['bg' => 'bg-emerald-500', 'text' => 'text-emerald-600', 'track' => 'bg-emerald-100'],
                        'rusak_ringan' => ['bg' => 'bg-amber-500', 'text' => 'text-amber-600', 'track' => 'bg-amber-100'],
                        'rusak_berat' => ['bg' => 'bg-rose-500', 'text' => 'text-rose-600', 'track' => 'bg-rose-100'],
                        'hilang' => ['bg' => 'bg-gray-500', 'text' => 'text-gray-600', 'track' => 'bg-gray-100'],
                    ];
                @endphp
                @foreach(['baik' => 'Baik', 'rusak_ringan' => 'Rusak Ringan', 'rusak_berat' => 'Rusak Berat', 'hilang' => 'Hilang'] as $key => $label)
                    @php
                        $count = $assetsCondition[$key] ?? 0;
                        $percentage = ($count / $total) * 100;
                    @endphp
                    <div>
                        <div class="flex justify-between text-sm mb-2">
                            <span class="text-gray-700 font-medium">{{ $label }}</span>
                            <span class="{{ $colors[$key]['text'] }} font-semibold">{{ $count }} <span class="text-gray-400 font-normal">({{ number_format($percentage, 0) }}%)</span></span>
                        </div>
                        <div class="w-full {{ $colors[$key]['track'] }} rounded-full h-2">
                            <div class="{{ $colors[$key]['bg'] }} h-2 rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
        
        <!-- Aset per Kategori -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Aset per Kategori</h3>
                <span class="text-xs text-gray-500 bg-gray-100 px-3 py-1 rounded-full">Top 5</span>
            </div>
            @if(count($assetsByCategory) > 0)
                <div class="space-y-3">
                    @foreach($assetsByCategory as $index => $cat)
                        <div class="flex items-center p-3 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center text-blue-600 font-bold">
                                {{ $index + 1 }}
                            </div>
                            <div class="ml-4 flex-1">
                                <p class="text-gray-900 font-medium">{{ $cat['name'] }}</p>
                            </div>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-medium">
                                {{ $cat['count'] }} aset
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>
                    <p class="text-gray-500">Belum ada data aset</p>
                </div>
            @endif
        </div>
    </div>
    
    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Maintenance Alerts -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <div class="flex items-center space-x-2">
                    <div class="w-2 h-2 bg-rose-500 rounded-full animate-pulse"></div>
                    <h3 class="text-lg font-semibold text-gray-900">Maintenance Alerts</h3>
                </div>
                @can('view-maintenances')
                <a href="{{ route('maintenances.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors" wire:navigate>
                    Lihat Semua →
                </a>
                @endcan
            </div>
            @if(count($maintenanceAlerts) > 0)
                <div class="space-y-3">
                    @foreach($maintenanceAlerts as $maintenance)
                        <div class="flex items-center p-4 bg-rose-50 border border-rose-100 rounded-xl">
                            <div class="flex-shrink-0">
                                <div class="w-10 h-10 rounded-lg bg-rose-100 flex items-center justify-center">
                                    <svg class="h-5 w-5 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $maintenance->asset->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 truncate">{{ $maintenance->ticket_number }}</p>
                            </div>
                            <span class="ml-2 px-2 py-1 text-xs bg-rose-100 text-rose-700 rounded-lg font-medium whitespace-nowrap">
                                {{ $maintenance->scheduled_date ? $maintenance->scheduled_date->format('d/m') : 'Segera' }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <div class="w-16 h-16 rounded-full bg-emerald-100 flex items-center justify-center mb-4">
                        <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                    </div>
                    <p class="text-gray-500">Semua aset dalam kondisi baik</p>
                </div>
            @endif
        </div>
        
        <!-- Recent Movements -->
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 p-6">
            <div class="flex justify-between items-center mb-6">
                <h3 class="text-lg font-semibold text-gray-900">Pergerakan Terakhir</h3>
                @can('view-movements')
                <a href="{{ route('movements.index') }}" class="text-blue-600 hover:text-blue-700 text-sm font-medium transition-colors" wire:navigate>
                    Lihat Semua →
                </a>
                @endcan
            </div>
            @if(count($recentMovements) > 0)
                <div class="space-y-3">
                    @foreach($recentMovements as $movement)
                        <div class="flex items-center p-4 bg-gray-50 rounded-xl hover:bg-gray-100 transition-colors">
                            <div class="flex-shrink-0">
                                @if($movement->type === 'peminjaman')
                                    <div class="w-10 h-10 rounded-lg bg-blue-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                        </svg>
                                    </div>
                                @elseif($movement->type === 'pengembalian')
                                    <div class="w-10 h-10 rounded-lg bg-emerald-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                                        </svg>
                                    </div>
                                @else
                                    <div class="w-10 h-10 rounded-lg bg-amber-100 flex items-center justify-center">
                                        <svg class="h-5 w-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                        </svg>
                                    </div>
                                @endif
                            </div>
                            <div class="ml-4 flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $movement->asset->name ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ ucfirst($movement->type) }} 
                                    @if($movement->toLocation)
                                        → {{ $movement->toLocation->name }}
                                    @endif
                                    @if($movement->toUser)
                                        → {{ $movement->toUser->name }}
                                    @endif
                                </p>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap ml-2">
                                {{ $movement->created_at->diffForHumans() }}
                            </span>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="flex flex-col items-center justify-center py-8 text-center">
                    <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                    </svg>
                    <p class="text-gray-500">Belum ada pergerakan aset</p>
                </div>
            @endif
        </div>
    </div>
</div>
