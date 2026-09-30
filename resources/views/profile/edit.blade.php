<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-bold text-2xl text-brand-blue leading-tight flex items-center gap-2">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                {{ __('Pengaturan Profil') }}
            </h2>
            <a href="{{ route('user.profile', Auth::user()) }}" class="hidden sm:flex items-center gap-2 text-sm text-brand-tosca hover:text-teal-700 font-bold transition-colors border border-brand-tosca px-4 py-2 rounded-lg hover:bg-brand-tosca hover:text-white">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                Lihat Profil Publik
            </a>
        </div>
    </x-slot>

    <div class="py-8 bg-gray-50 min-h-screen">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            @if(!Auth::user()->hasRole('merchant') && !Auth::user()->hasRole('driver') && !Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('hse'))
                <div class="relative overflow-hidden bg-gradient-to-r from-brand-blue to-blue-800 rounded-3xl p-8 shadow-xl flex flex-col sm:flex-row items-center justify-between text-white border border-blue-900/30">
                    <div class="absolute top-0 right-0 -mr-10 -mt-10 w-40 h-40 rounded-full bg-brand-tosca/20 blur-2xl"></div>
                    <div class="mb-6 sm:mb-0 relative z-10">
                        <h4 class="text-2xl font-bold mb-2 flex items-center gap-2">
                            <span class="text-3xl">🚀</span> Ayo Pilih Role-mu Sekarang!
                        </h4>
                        <p class="text-blue-100 text-sm max-w-xl">Akun Anda saat ini berstatus <strong class="text-white">COMMON</strong>. Upgrade akunmu menjadi Driver atau Merchant untuk bisa mulai mencari muatan atau menawarkan muatan dengan fitur lengkap KargoKita.</p>
                    </div>
                    <div class="flex flex-col sm:flex-row gap-4 relative z-10 w-full sm:w-auto">
                        <a href="{{ route('verification') }}" class="px-6 py-3 bg-white/10 backdrop-blur border border-white/30 text-white font-bold rounded-xl hover:bg-white/20 transition text-center shadow-sm">Jadi Merchant</a>
                        <a href="{{ route('driver.verification') }}" class="px-6 py-3 bg-brand-tosca text-white font-bold rounded-xl hover:bg-teal-500 shadow-lg hover:shadow-xl transition transform hover:-translate-y-0.5 text-center">Jadi Driver</a>
                    </div>
                </div>
            @endif

            <!-- Profile Overview Card -->
            <div class="bg-white shadow-sm sm:rounded-3xl border border-gray-100 p-8 sm:p-10 relative overflow-hidden">
                <!-- Decorative accents -->
                <div class="absolute top-0 right-0 w-64 h-64 bg-brand-blue/5 rounded-full blur-3xl -mr-20 -mt-20 pointer-events-none"></div>
                <div class="absolute bottom-0 left-0 w-40 h-40 bg-brand-tosca/5 rounded-full blur-2xl -ml-10 -mb-10 pointer-events-none"></div>
                
                <div class="flex flex-col sm:flex-row items-center gap-8 relative z-10">
                    <!-- Avatar -->
                    <div class="relative group">
                        <div class="w-28 h-28 sm:w-32 sm:h-32 rounded-full overflow-hidden border-4 border-white shadow-lg relative z-10 ring-4 ring-gray-50">
                            <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=1D446F&color=fff&size=256" alt="{{ Auth::user()->name }}" class="w-full h-full object-cover">
                        </div>
                        <button class="absolute inset-0 z-20 flex items-center justify-center bg-brand-blue/60 text-white rounded-full opacity-0 group-hover:opacity-100 transition-opacity backdrop-blur-sm cursor-pointer" onclick="alert('Fitur upload foto segera hadir!')">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                        </button>
                    </div>

                    <!-- Short Info -->
                    <div class="flex-1 text-center sm:text-left">
                        <h3 class="text-3xl font-extrabold text-brand-black mb-1">{{ Auth::user()->name }}</h3>
                        <p class="text-gray-500 mb-4">{{ Auth::user()->email }}</p>
                        <div class="flex flex-wrap justify-center sm:justify-start gap-3">
                            <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-blue-50 text-brand-blue border border-blue-100">
                                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                                {{ ucfirst(Auth::user()->roles->first()?->name ?? 'User') }}
                            </span>
                            @if(Auth::user()->tier)
                                <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-gradient-to-r from-yellow-100 to-yellow-50 text-yellow-800 border border-yellow-200">
                                    <svg class="w-4 h-4 mr-1.5 text-yellow-500" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    Tier: {{ strtoupper(Auth::user()->tier) }}
                                </span>
                            @endif
                            <a href="{{ route('user.profile', Auth::user()) }}" class="sm:hidden inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-brand-tosca text-white">
                                Lihat Profil Publik
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Settings Grid -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
                <!-- Personal Info -->
                <div class="bg-white shadow-sm sm:rounded-3xl border border-gray-100 overflow-hidden relative">
                    <div class="h-1 w-full bg-brand-blue"></div>
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="p-2.5 bg-blue-50 rounded-xl text-brand-blue">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-brand-black">Informasi Pribadi</h3>
                        </div>
                        @include('profile.partials.update-profile-information-form')
                    </div>
                </div>

                <!-- Password -->
                <div class="bg-white shadow-sm sm:rounded-3xl border border-gray-100 overflow-hidden relative">
                    <div class="h-1 w-full bg-brand-tosca"></div>
                    <div class="p-8">
                        <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                            <div class="p-2.5 bg-teal-50 rounded-xl text-brand-tosca">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                            </div>
                            <h3 class="text-xl font-bold text-brand-black">Keamanan Password</h3>
                        </div>
                        @include('profile.partials.update-password-form')
                    </div>
                </div>
            </div>

            <!-- Profile Gallery -->
            @if(Auth::user()->hasRole('driver') || Auth::user()->hasRole('merchant'))
            <div class="bg-white shadow-sm sm:rounded-3xl border border-gray-100 overflow-hidden relative">
                <div class="h-1 w-full bg-blue-500"></div>
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-gray-100">
                        <div class="p-2.5 bg-blue-50 rounded-xl text-blue-500">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-xl font-bold text-brand-black">Galeri Portofolio</h3>
                            <p class="text-xs text-gray-500">Unggah foto {{ Auth::user()->hasRole('driver') ? 'armada atau kelengkapan operasional' : 'gudang atau contoh muatan' }} untuk meningkatkan kepercayaan.</p>
                        </div>
                    </div>
                    @livewire('profile-gallery')
                </div>
            </div>
            @endif

            <!-- Danger Zone -->
            <div class="bg-white shadow-sm sm:rounded-3xl border border-red-100 overflow-hidden mt-8 relative">
                <div class="h-1 w-full bg-red-500"></div>
                <div class="p-8">
                    <div class="flex items-center gap-3 mb-6 pb-4 border-b border-red-50">
                        <div class="p-2.5 bg-red-50 rounded-xl text-red-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        </div>
                        <h3 class="text-xl font-bold text-red-600">Zona Berbahaya</h3>
                    </div>
                    @include('profile.partials.delete-user-form')
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
