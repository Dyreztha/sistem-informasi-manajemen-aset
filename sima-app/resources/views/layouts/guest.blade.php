<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'SIMA') }} - Login</title>
        
        <!-- Favicon -->
        <link rel="icon" type="image/png" href="{{ asset('media/images/sima-icon.png') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <style>
            body { font-family: 'Inter', sans-serif; }
        </style>
    </head>
    <body class="font-sans antialiased bg-gray-100">
        <div class="min-h-screen flex items-center justify-center p-6">
            <div class="w-full max-w-md">
                <!-- Logo -->
                <div class="flex justify-center mb-8">
                    <div class="flex items-center space-x-3">
                        <img src="{{ asset('media/images/sima-icon.png') }}" alt="SIMA" class="h-12">
                        <span class="text-2xl font-bold text-gray-900">SIMA</span>
                    </div>
                </div>
                
                <!-- Card -->
                <div class="bg-white rounded-2xl shadow-sm border border-gray-200 p-8">
                    <div class="text-center mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">Selamat Datang</h2>
                        <p class="text-gray-500 mt-1">Masuk ke akun Anda</p>
                    </div>
                    
                    {{ $slot }}
                </div>
                
                <p class="text-center text-gray-400 text-sm mt-6">
                    &copy; {{ date('Y') }} SIMA
                </p>
            </div>
        </div>
    </body>
</html>
