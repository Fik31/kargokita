<!-- Sidebar Backdrop (Mobile) -->
<div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-40 bg-gray-900 bg-opacity-50 transition-opacity md:hidden" @click="sidebarOpen = false"></div>

<!-- Sidebar -->
<aside x-cloak :class="{ 'translate-x-0': sidebarOpen, '-translate-x-full': !sidebarOpen }" class="fixed inset-y-0 left-0 z-50 w-64 bg-white border-r border-gray-200 transform transition-transform duration-300 ease-in-out md:translate-x-0 md:static md:inset-0 flex flex-col h-full shadow-lg md:shadow-none">
    
    <!-- Logo -->
    <div class="flex items-center justify-between h-16 px-4 border-b border-gray-100 shrink-0">
        <a href="{{ route('feed') }}" class="flex items-center gap-2 group">
            <img src="{{ asset('assets/images/logo-white.png') }}" alt="Kargokita Logo" class="w-full h-auto object-cover object-left py-1">
        </a>
        <button @click="sidebarOpen = false" class="md:hidden text-gray-500 hover:text-gray-700 bg-gray-50 p-1.5 rounded-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
        </button>
    </div>

    <!-- Navigation Links -->
    <nav class="flex-1 px-3 py-4 space-y-1 overflow-y-auto font-medium">
        <!-- Dashboard / Feed -->
        <a @click="sidebarOpen = false" id="nav-feed" href="{{ route('feed') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('feed') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('feed') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2 2 0 00-2-2h-2m-4-3H9M7 16h6M7 8h6v4H7V8z"></path></svg>
            Feed Utama
        </a>

        <!-- Chat -->
        <a @click="sidebarOpen = false" id="nav-chat" href="{{ route('chat') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('chat') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
            <svg class="w-5 h-5 {{ request()->routeIs('chat') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
            Pesan (Chat)
        </a>

        <!-- Bidding Menu -->
        @if(Auth::user()->hasRole('administrator'))
            <div class="pt-5 pb-2">
                <p class="px-3 text-[0.7rem] font-bold text-gray-400 uppercase tracking-wider">Lelang & Bidding</p>
            </div>
            <a @click="sidebarOpen = false" href="{{ route('admin.bidding') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.bidding') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.bidding') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Kelola Bidding
            </a>
            <a @click="sidebarOpen = false" href="{{ route('admin.bidding-settings') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.bidding-settings') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.bidding-settings') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                Setting Top Bid
            </a>
        @elseif(!Auth::user()->hasRole('hse'))
            <div class="pt-5 pb-2">
                <p class="px-3 text-[0.7rem] font-bold text-gray-400 uppercase tracking-wider">Bursa DO</p>
            </div>
            @if(Auth::user()->hasRole('merchant'))
                <a href="{{ route('merchant.loads') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('merchant.loads') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('merchant.loads') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                    Daftar Order
                </a>
                <a @click="sidebarOpen = false" href="{{ route('bidding.create') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('bidding.create') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('bidding.create') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Buat Order Baru
                </a>
                <a @click="sidebarOpen = false" href="{{ route('bidding') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('bidding') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('bidding') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Bursa Muatan (LTL)
                </a>
            @else
                <a @click="sidebarOpen = false" id="nav-bidding" href="{{ route('bidding') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('bidding') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('bidding') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                    Cari Muatan
                </a>
            @endif
        @endif

        <!-- Tracking & Operasional -->
        @if(Auth::user()->hasRole('administrator') || Auth::user()->hasRole('merchant') || Auth::user()->hasRole('driver'))
            <div class="pt-5 pb-2">
                <p class="px-3 text-[0.7rem] font-bold text-gray-400 uppercase tracking-wider">Operasional</p>
            </div>
            
            <!-- WOW Feature Menu -->
            <a @click="sidebarOpen = false" href="{{ route('3d-optimizer') }}" class="flex items-center gap-3 px-3 py-2.5 mt-1 rounded-lg transition-all {{ request()->routeIs('3d-optimizer') ? 'bg-gradient-to-r from-brand-blue to-blue-600 text-white shadow-md' : 'bg-blue-50/50 border border-blue-200 text-gray-700 hover:bg-blue-100 hover:text-brand-blue hover:shadow-sm' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('3d-optimizer') ? 'text-yellow-300' : 'text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10l-2 1m0 0l-2-1m2 1v2.5M20 7l-2 1m2-1l-2-1m2 1v2.5M14 4l-2-1-2 1M4 7l2-1M4 7l2 1M4 7v2.5M12 21l-2-1m2 1l2-1m-2 1v-2.5M6 18l-2-1v-2.5M18 18l2-1v-2.5"></path></svg>
                <span class="font-bold tracking-wide {{ request()->routeIs('3d-optimizer') ? '' : 'text-brand-blue' }}">3D LTL Optimizer</span>
                <span class="ml-auto flex h-2.5 w-2.5 relative">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ request()->routeIs('3d-optimizer') ? 'bg-yellow-400' : 'bg-blue-400' }} opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2.5 w-2.5 {{ request()->routeIs('3d-optimizer') ? 'bg-yellow-500' : 'bg-brand-blue' }}"></span>
                </span>
            </a>
            
            <a @click="sidebarOpen = false" href="{{ route('command-center') }}" class="flex items-center gap-3 px-3 py-2.5 mt-2 rounded-lg transition-all {{ request()->routeIs('command-center') ? 'bg-gradient-to-r from-gray-900 to-gray-800 text-emerald-400 shadow-md ring-1 ring-gray-700' : 'bg-gray-50 border border-gray-200 text-gray-700 hover:bg-gray-100 hover:text-gray-900 hover:shadow-sm' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('command-center') ? 'text-emerald-400' : 'text-gray-500' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                <span class="font-bold tracking-wide {{ request()->routeIs('command-center') ? '' : 'text-gray-600' }}">Command Center</span>
                <span class="ml-auto flex h-2 w-2 relative">
                  <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ request()->routeIs('command-center') ? 'bg-emerald-400' : 'bg-gray-400' }} opacity-75"></span>
                  <span class="relative inline-flex rounded-full h-2 w-2 {{ request()->routeIs('command-center') ? 'bg-emerald-500' : 'bg-gray-500' }}"></span>
                </span>
            </a>
        @endif
        
        @if(Auth::user()->hasRole('administrator') || Auth::user()->hasRole('merchant'))
            <a @click="sidebarOpen = false" href="{{ route('tracking') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('tracking') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('tracking') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                Live Tracking
            </a>
        @endif
        
        @if(Auth::user()->hasRole('driver'))
            <a @click="sidebarOpen = false" href="{{ route('cockpit') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('cockpit') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('cockpit') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                Driver Cockpit
            </a>
        @endif

        @if(Auth::user()->hasRole('administrator') || Auth::user()->hasRole('merchant') || Auth::user()->hasRole('driver'))
            <a @click="sidebarOpen = false" href="{{ route('history') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('history') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('history') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Riwayat Perjalanan
            </a>
        @endif

        @if(Auth::user()->hasRole('merchant') || Auth::user()->hasRole('driver'))
            <a @click="sidebarOpen = false" href="{{ route('reports') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('reports') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('reports') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                Laporan & Analitik
            </a>
            
            <div class="pt-5 pb-2">
                <p class="px-3 text-[0.7rem] font-bold text-gray-400 uppercase tracking-wider">Keuangan & Benefit</p>
            </div>
            <a @click="sidebarOpen = false" href="{{ route('subscription') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('subscription') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('subscription') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 3v4M3 5h4M6 17v4m-2-2h4m5-16l2.286 6.857L21 12l-5.714 2.143L13 21l-2.286-6.857L5 12l5.714-2.143L13 3z"></path></svg>
                Deposit Jaminan
            </a>
            <a @click="sidebarOpen = false" href="{{ route('wallet') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('wallet') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('wallet') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                Dompet Saya
            </a>
        @endif

        @if(Auth::user()->hasRole('driver'))
            <a @click="sidebarOpen = false" href="{{ route('branding') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('branding') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('branding') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 21a4 4 0 01-4-4V5a2 2 0 012-2h4a2 2 0 012 2v12a4 4 0 01-4 4zm0 0h12a2 2 0 002-2v-4a2 2 0 00-2-2h-2.343M11 7.343l1.657-1.657a2 2 0 012.828 0l2.829 2.829a2 2 0 010 2.828l-8.486 8.485M7 17h.01"></path></svg>
                Branding Truk
            </a>
        @endif

        @if(Auth::user()->hasRole('administrator') || Auth::user()->hasRole('hse'))
            <div class="pt-5 pb-2">
                <p class="px-3 text-[0.7rem] font-bold text-gray-400 uppercase tracking-wider">Sistem & Assessment</p>
            </div>
            
            @if(Auth::user()->hasRole('administrator'))
                <a @click="sidebarOpen = false" href="{{ route('admin.merchant-assessment.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.merchant-assessment.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.merchant-assessment.dashboard') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                    Dashboard Merchant
                </a>
                <a @click="sidebarOpen = false" href="{{ route('admin.merchant-assessment.verification-list') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.merchant-assessment.verification-list') || request()->routeIs('admin.merchant-assessment.form') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                    <svg class="w-5 h-5 {{ request()->routeIs('admin.merchant-assessment.verification-list') || request()->routeIs('admin.merchant-assessment.form') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    Verifikasi Merchant
                </a>
            @endif
            
            <a @click="sidebarOpen = false" href="{{ route('admin.assessment.dashboard') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.assessment.dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.assessment.dashboard') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20.488 9H15V3.512A9.025 9.025 0 0120.488 9z"></path></svg>
                Dashboard HSE
            </a>
            
            <a @click="sidebarOpen = false" id="nav-verification" href="{{ route('admin.assessment.verification-list') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.assessment.verification-list') || request()->routeIs('admin.assessment.form') ? 'bg-blue-50 text-blue-600' : 'text-gray-600 hover:bg-gray-50 hover:text-gray-900' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.assessment.verification-list') || request()->routeIs('admin.assessment.form') ? 'text-blue-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
                Verifikasi HSE
            </a>
            
            <a @click="sidebarOpen = false" id="nav-disputes" href="{{ route('admin.assessment.disputes') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg transition-all {{ request()->routeIs('admin.assessment.disputes') ? 'bg-red-50 text-red-600' : 'text-gray-600 hover:bg-red-50 hover:text-red-600' }}">
                <svg class="w-5 h-5 {{ request()->routeIs('admin.assessment.disputes') ? 'text-red-600' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                Darurat / Mediasi
            </a>
        @endif
    </nav>

    <!-- User Profile Footer -->
    <div id="nav-profile" class="p-4 border-t border-gray-100 shrink-0">
        <div x-data="{ userMenuOpen: false }" class="relative">
            <button @click="userMenuOpen = !userMenuOpen" @click.away="userMenuOpen = false" class="flex items-center w-full gap-3 p-2 rounded-lg hover:bg-gray-50 transition-colors focus:outline-none">
                <div class="flex items-center justify-center w-10 h-10 rounded-full bg-blue-100 text-blue-600 font-bold shrink-0">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div class="flex flex-col text-left truncate flex-1">
                    <div class="flex items-center gap-1">
                        <span class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->name }}</span>
                        @if(Auth::user()->tier && !Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('hse'))
                            <x-tier-badge :tier="Auth::user()->tier" class="scale-[0.8] origin-left" />
                        @endif
                    </div>
                    <span class="text-xs text-gray-500 truncate">{{ Auth::user()->email }}</span>
                </div>
                <svg class="w-5 h-5 text-gray-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"></path></svg>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="userMenuOpen" style="display: none;"
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                 class="absolute bottom-full left-0 w-full mb-2 bg-white rounded-lg shadow-xl border border-gray-100 py-1 overflow-hidden z-50">
                <a href="{{ route('profile.edit') }}" @click="sidebarOpen = false" class="block px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600">Profil Saya</a>
                @if(Auth::user()->hasRole('merchant'))
                    <a href="{{ route('verification') }}" @click="sidebarOpen = false" class="block px-4 py-2.5 text-sm font-bold text-blue-600 hover:bg-blue-50">Verifikasi & Tier</a>
                @elseif(Auth::user()->hasRole('driver'))
                    <a href="{{ route('driver.verification') }}" @click="sidebarOpen = false" class="block px-4 py-2.5 text-sm font-bold text-blue-600 hover:bg-blue-50">Form Pendaftaran & Verifikasi</a>
                    <a href="{{ route('driver.hse-assessment') }}" @click="sidebarOpen = false" class="block px-4 py-2.5 text-sm font-bold text-blue-600 hover:bg-blue-50">HSE Audit Assessment</a>
                @endif
                <div class="h-px bg-gray-100 my-1"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2.5 text-sm font-medium text-gray-700 hover:bg-gray-50 hover:text-blue-600">
                        Keluar (Log Out)
                    </button>
                </form>
            </div>
        </div>
    </div>
</aside>
