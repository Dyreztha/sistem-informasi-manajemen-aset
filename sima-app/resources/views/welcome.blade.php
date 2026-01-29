<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>SIMA - Sistem Informasi Manajemen Aset</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700,800&display=swap" rel="stylesheet" />

        <!-- Styles -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <style>
            body { font-family: 'Inter', sans-serif; }


        </style>
    </head>
    <body class="antialiased bg-gray-50">
        <div class="min-h-screen">
            <!-- Navigation -->
            <nav class="fixed top-0 left-0 right-0 z-50 bg-white border-b border-gray-200 animate-fadeinUpper">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-16">
                        <div class="flex items-center">
                            <img src="{{ asset('media/images/sima-icon.png') }}" alt="SIMA" class="h-8 w-8">
                            <span class="ml-2 text-xl font-bold text-gray-900">SIMA</span>
                        </div>
                        @if (Route::has('login'))
                            <livewire:welcome.navigation />
                        @endif
                    </div>
                </div>
            </nav>

            <!-- Hero Section -->
            <div class="relative pt-32 pb-20 px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 via-purple-50 to-pink-50 overflow-hidden">

                <div class="absolute top-20 left-10 w-96 h-96 bg-blue-300/40 rounded-full blur-3xl"></div>
                <div class="absolute top-40 right-10 w-96 h-96 bg-purple-300/40 rounded-full blur-3xl"></div>
                <div class="absolute bottom-20 left-1/3 w-96 h-96 bg-pink-300/30 rounded-full blur-3xl"></div>

                <div class="relative max-w-7xl mx-auto text-center animate-fadeinUpper">
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-extrabold text-gray-900 mb-6">
                        Sistem Informasi<br>
                        <span class="text-blue-600">Manajemen Aset</span>
                    </h1>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto mb-10">
                        Kelola aset perusahaan Anda dengan mudah, efisien, dan terstruktur. Pantau inventaris, lacak pergerakan, dan optimalkan nilai aset dalam satu platform terintegrasi.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('login') }}" class="px-8 py-4 bg-blue-600 hover:bg-blue-700 hover:scale-110 text-white rounded-xl font-semibold shadow-sm transition-transform duration-[0.4s]">
                            Mulai Sekarang
                        </a>
                        <a href="#features" class="px-8 py-4 bg-white text-gray-700 rounded-xl hover:scale-110 font-semibold border border-gray-300 hover:bg-gray-100 transition-all duration-[0.4s]">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
            </div>

            <!-- Features Section -->
            <div id="features" class="py-20 px-4 sm:px-6 lg:px-8 bg-gray-50 animate-fadeinLower">
                <div class="max-w-7xl mx-auto">
                    <div class="text-center mb-16">
                        <h2 class="text-3xl font-bold text-gray-900 mb-4">Fitur Unggulan</h2>
                        <p class="text-gray-600 max-w-2xl mx-auto">Solusi lengkap untuk manajemen aset perusahaan Anda</p>
                    </div>

                    <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-8">
                        <!-- Feature 1 -->
                        <div class="p-6 bg-white rounded-2xl border border-gray-200 hover:shadow-lg hover:shadow-blue-300 shadow-sm hover:translate-y-[-10px] transition-transform duration-300 ease-out">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen Inventaris</h3>
                            <p class="text-gray-600 text-sm">Kelola semua aset dengan sistem pencatatan yang terstruktur dan mudah dicari.</p>
                        </div>

                        <!-- Feature 2 -->
                        <div class="p-6 bg-white rounded-2xl border border-gray-200 hover:shadow-lg hover:shadow-emerald-300 shadow-sm hover:translate-y-[-10px] transition-transform duration-300 ease-out">
                            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Perhitungan Depresiasi</h3>
                            <p class="text-gray-600 text-sm">Hitung nilai penyusutan aset secara otomatis dengan berbagai metode akuntansi.</p>
                        </div>

                        <!-- Feature 3 -->
                        <div class="p-6 bg-white rounded-2xl border border-gray-200 hover:shadow-lg hover:shadow-amber-300 shadow-sm hover:translate-y-[-10px] transition-transform duration-300 ease-out">
                            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197m9 5.197v-1a6 6 0 00-3-5.197"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Tracking Pergerakan</h3>
                            <p class="text-gray-600 text-sm">Lacak perpindahan dan penugasan aset ke berbagai lokasi dan pengguna.</p>
                        </div>

                        <!-- Feature 4 -->
                        <div class="p-6 bg-white rounded-2xl border border-gray-200 hover:shadow-lg hover:shadow-rose-300 shadow-sm hover:translate-y-[-10px] transition-transform duration-300 ease-out">
                            <div class="w-12 h-12 bg-rose-100 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-rose-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Manajemen Pemeliharaan</h3>
                            <p class="text-gray-600 text-sm">Jadwalkan dan catat semua aktivitas maintenance untuk menjaga kondisi aset.</p>
                        </div>

                        <!-- Feature 5 -->
                        <div class="p-6 bg-white rounded-2xl border border-gray-200 hover:shadow-lg hover:shadow-purple-300 shadow-sm hover:translate-y-[-10px] transition-transform duration-300 ease-out">
                            <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v1m6 11h2m-6 0h-2v4m0-11v3m0 0h.01M12 12h4.01M16 20h4M4 12h4m12 0h.01M5 8h2a1 1 0 001-1V5a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1zm12 0h2a1 1 0 001-1V5a1 1 0 00-1-1h-2a1 1 0 00-1 1v2a1 1 0 001 1zM5 20h2a1 1 0 001-1v-2a1 1 0 00-1-1H5a1 1 0 00-1 1v2a1 1 0 001 1z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">QR Code & Labeling</h3>
                            <p class="text-gray-600 text-sm">Generate QR code untuk setiap aset untuk memudahkan identifikasi dan stock opname.</p>
                        </div>

                        <!-- Feature 6 -->
                        <div class="p-6 bg-white rounded-2xl border border-gray-200 hover:shadow-lg hover:shadow-cyan-300 shadow-sm hover:translate-y-[-10px] transition-transform duration-300 ease-out">
                            <div class="w-12 h-12 bg-cyan-100 rounded-xl flex items-center justify-center mb-4">
                                <svg class="w-6 h-6 text-cyan-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <h3 class="text-lg font-semibold text-gray-900 mb-2">Laporan & Analitik</h3>
                            <p class="text-gray-600 text-sm">Dapatkan insight dengan laporan dan analitik yang komprehensif.</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <footer class="py-8 px-4 sm:px-6 lg:px-8 bg-gray-50 border-t border-gray-200">
                <div class="max-w-7xl mx-auto text-center">
                    <div class="flex items-center justify-center mb-4">
                        <img src="{{ asset('media/images/sima-icon.png') }}" alt="SIMA" class="h-6 w-6">
                        <span class="ml-2 font-bold text-gray-900">SIMA</span>
                    </div>
                    <p class="text-gray-500 text-sm">&copy; {{ date('Y') }} SIMA - Sistem Informasi Manajemen Aset. All rights reserved.</p>
                </div>
            </footer>
        </div>
    </body>
</html>
