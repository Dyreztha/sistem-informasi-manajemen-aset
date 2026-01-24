<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMA') }} - Sistem Informasi Manajemen Aset</title>
        
        <link rel="icon" type="image/png" href="{{ asset('media/images/sima-icon.png') }}">
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
            .sidebar-link { transition: all 0.15s ease; }
            .sidebar-link:hover { background-color: #f3f4f6; }
            .sidebar-link.active { background-color: #eff6ff; color: #2563eb; border-right: 2px solid #2563eb; }
        </style>
        
        @stack('styles')
    </head>
    <body class="font-sans antialiased bg-gray-50" x-data="{ sidebarOpen: false }">
        <div class="min-h-screen flex">
            <!-- Mobile Overlay -->
            <div x-show="sidebarOpen" @click="sidebarOpen = false" class="fixed inset-0 bg-gray-900/50 z-20 lg:hidden"></div>
            
            <!-- Sidebar -->
            <aside class="fixed inset-y-0 left-0 w-64 bg-white border-r border-gray-200 z-30 transform transition-transform lg:translate-x-0"
                   :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full lg:translate-x-0'">
                
                <!-- Logo -->
                <div class="flex items-center h-16 px-6 border-b border-gray-200">
                    <a href="{{ route('dashboard') }}" wire:navigate class="flex items-center space-x-3">
                        <img src="{{ asset('media/images/sima-icon.png') }}" alt="SIMA" class="h-8 w-8">
                        <span class="text-xl font-bold text-gray-900">SIMA</span>
                    </a>
                </div>
                
                <!-- Navigation -->
                <nav class="p-4 space-y-1 overflow-y-auto" style="height: calc(100vh - 140px);">
                    <a href="{{ route('dashboard') }}" wire:navigate
                       class="sidebar-link flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('dashboard') ? 'active' : 'text-gray-700' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Dashboard
                    </a>
                    
                    <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase">Master Data</p>
                    
                    @can('view-assets')
                    <a href="{{ route('assets.index') }}" wire:navigate
                       class="sidebar-link flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('assets.*') ? 'active' : 'text-gray-700' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                        </svg>
                        Aset
                    </a>
                    @endcan
                    
                    @can('view-categories')
                    <a href="{{ route('categories.index') }}" wire:navigate
                       class="sidebar-link flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('categories.*') ? 'active' : 'text-gray-700' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A2 2 0 013 12V7a4 4 0 014-4z"/>
                        </svg>
                        Kategori
                    </a>
                    @endcan
                    
                    @can('view-locations')
                    <a href="{{ route('locations.index') }}" wire:navigate
                       class="sidebar-link flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('locations.*') ? 'active' : 'text-gray-700' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Lokasi
                    </a>
                    @endcan
                    
                    @can('view-vendors')
                    <a href="{{ route('vendors.index') }}" wire:navigate
                       class="sidebar-link flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('vendors.*') ? 'active' : 'text-gray-700' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                        </svg>
                        Vendor
                    </a>
                    @endcan
                    
                    <p class="px-3 pt-4 pb-2 text-xs font-semibold text-gray-400 uppercase">Transaksi</p>
                    
                    @can('view-movements')
                    <a href="{{ route('movements.index') }}" wire:navigate
                       class="sidebar-link flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('movements.*') ? 'active' : 'text-gray-700' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                        </svg>
                        Sirkulasi
                    </a>
                    @endcan
                    
                    @can('view-maintenances')
                    <a href="{{ route('maintenances.index') }}" wire:navigate
                       class="sidebar-link flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('maintenances.*') ? 'active' : 'text-gray-700' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        Pemeliharaan
                    </a>
                    @endcan
                    
                    @can('view-stock-opnames')
                    <a href="{{ route('stock-opnames.index') }}" wire:navigate
                       class="sidebar-link flex items-center px-3 py-2.5 text-sm font-medium rounded-lg {{ request()->routeIs('stock-opnames.*') ? 'active' : 'text-gray-700' }}">
                        <svg class="w-5 h-5 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        Stock Opname
                    </a>
                    @endcan
                </nav>
                
                <!-- User Info -->
                <div class="absolute bottom-0 left-0 right-0 p-4 border-t border-gray-200 bg-white">
                    <div class="flex items-center space-x-3">
                        <div class="w-9 h-9 rounded-full bg-blue-600 flex items-center justify-center">
                            <span class="text-white font-medium text-sm">{{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 2)) }}</span>
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-gray-900 truncate">{{ auth()->user()->name ?? 'User' }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->roles->first()->name ?? 'Staff' }}</p>
                        </div>
                    </div>
                </div>
            </aside>
            
            <!-- Main Content -->
            <div class="flex-1 lg:ml-64">
                <!-- Header -->
                <header class="sticky top-0 z-20 bg-white border-b border-gray-200">
                    <div class="flex items-center justify-between h-16 px-4 lg:px-8">
                        <button @click="sidebarOpen = !sidebarOpen" class="lg:hidden p-2 rounded-lg text-gray-500 hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 6h16M4 12h16M4 18h16"/>
                            </svg>
                        </button>
                        
                        @if (isset($header))
                            <h1 class="hidden lg:block text-lg font-semibold text-gray-900">{{ $header }}</h1>
                        @else
                            <div></div>
                        @endif
                        
                        <div class="flex items-center space-x-3">
                            <button class="p-2 rounded-lg text-gray-500 hover:bg-gray-100 relative">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"/>
                                </svg>
                            </button>
                            
                            <div x-data="{ open: false }" class="relative">
                                <button @click="open = !open" class="flex items-center space-x-2 p-2 rounded-lg text-gray-700 hover:bg-gray-100">
                                    <span class="hidden sm:block text-sm font-medium">{{ auth()->user()->name ?? 'User' }}</span>
                                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 9l-7 7-7-7"/>
                                    </svg>
                                </button>
                                
                                <div x-show="open" @click.away="open = false" x-transition
                                     class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-1 z-50">
                                    <a href="{{ route('profile') }}" wire:navigate class="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                        <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                        </svg>
                                        Profile
                                    </a>
                                    <hr class="my-1 border-gray-100">
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="flex items-center w-full px-4 py-2 text-sm text-gray-700 hover:bg-gray-50">
                                            <svg class="w-4 h-4 mr-3 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                            </svg>
                                            Logout
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </header>

                <main class="p-4 lg:p-8">
                    {{ $slot }}
                </main>
                
                <footer class="border-t border-gray-200 px-4 lg:px-8 py-4">
                    <p class="text-center text-sm text-gray-500">&copy; {{ date('Y') }} SIMA - Sistem Informasi Manajemen Aset</p>
                </footer>
            </div>
        </div>
        
        @stack('scripts')
    </body>
</html>
