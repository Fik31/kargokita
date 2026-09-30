<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'KARGOKITA') }}</title>
        <link rel="icon" type="image/jpeg" href="{{ asset('assets/images/icon.jpg') }}">

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <!-- Leaflet CSS & JS -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
        <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
        
        <!-- Leaflet Routing Machine -->
        <link rel="stylesheet" href="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.css" />
        <script src="https://unpkg.com/leaflet-routing-machine@latest/dist/leaflet-routing-machine.js"></script>
    </head>
    <body class="font-sans antialiased">
        <div x-data="{ sidebarOpen: false }" class="flex h-screen bg-gray-100 overflow-hidden">
            <!-- Sidebar -->
            @include('layouts.navigation')

            <!-- Main Content Area -->
            <div class="flex-1 flex flex-col overflow-hidden">
                @php
                    $hasNewTrip = \App\Models\Trip::where('status', 'assigned')
                        ->where(function($q) {
                            $q->where('driver_id', auth()->id())
                              ->orWhereHas('cargo', function($sq) {
                                  $sq->where('merchant_id', auth()->id());
                              });
                        })->exists();
                @endphp
                @if($hasNewTrip && !request()->routeIs('chat'))
                    <div class="bg-brand-blue text-white px-4 py-3 shadow-md flex justify-between items-center z-50 shrink-0">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm font-medium leading-tight">Selamat! Ada order yang disetujui. Segera bergabung ke kolom Chat untuk komunikasi lebih lanjut.</span>
                        </div>
                        <a href="{{ route('chat') }}" class="ml-3 whitespace-nowrap inline-flex items-center justify-center px-3 py-1.5 border border-transparent rounded-full shadow-sm text-xs font-bold text-brand-blue bg-white hover:bg-gray-50 transition">
                            Buka Chat
                        </a>
                    </div>
                @endif
                <!-- Mobile Top Bar -->
                <div class="md:hidden flex items-center justify-between bg-white border-b border-gray-200 px-4 py-3 z-20">
                    <div class="flex items-center gap-4">
                        <button @click="sidebarOpen = true" class="text-gray-500 hover:text-gray-700 focus:outline-none focus:ring-2 focus:ring-blue-500 rounded-md p-1 -ml-1">
                            <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                        </button>
                        <a href="{{ route('feed') ?? '#' }}" class="flex items-center">
                            <img src="{{ asset('assets/images/logo-white3.png') }}" alt="Kargokita Logo" class="h-7 w-auto object-contain">
                        </a>
                    </div>
                    <div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name ?? 'User') }}&background=0D8ABC&color=fff" class="w-8 h-8 rounded-full shadow-sm">
                    </div>
                </div>

                <!-- Page Heading -->
                @isset($header)
                    <header class="bg-white shadow z-10 shrink-0">
                        <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                            {{ $header }}
                        </div>
                    </header>
                @endisset

                <!-- Page Content -->
                <main class="flex-1 overflow-y-auto p-4 sm:p-6 lg:p-8 bg-gray-50">
                    {{ $slot }}
                </main>
            </div>
        </div>
    </body>
</html>
