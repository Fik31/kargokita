<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-xl font-bold mb-2 flex items-center gap-2">
                        Selamat Datang, {{ Auth::user()->name }}!
                        @if(Auth::user()->tier && !Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('hse'))
                            <x-tier-badge :tier="Auth::user()->tier" />
                        @endif
                    </h3>
                    @if(!Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('hse'))
                    <p class="mb-4">Saat ini akun Anda berada di tier <strong>{{ strtoupper(Auth::user()->tier ?? 'COMMON') }}</strong>.</p>
                    @endif
                    
                    @php
                        $user = Auth::user();
                        $hasRole = $user->hasRole('merchant') || $user->hasRole('driver') || $user->hasRole('administrator') || $user->hasRole('hse');
                        
                        $hasPendingDriver = false;
                        $hasPendingMerchant = false;
                        if (!$hasRole) {
                            $hasPendingDriver = \App\Models\VerificationRequest::where('user_id', $user->id)->where('status', 'pending')->exists();
                            // Merchant form uses Assessment table initially and reuses driver_id column for user_id
                            $hasPendingMerchant = \App\Models\Assessment::where('driver_id', $user->id)->where('status', 'SUBMITTED')->exists(); 
                        }
                    @endphp

                    @if(!$hasRole)
                        @if($hasPendingDriver || $hasPendingMerchant)
                            <div class="mt-8 bg-yellow-50 border border-yellow-400 rounded-xl p-6">
                                <h4 class="text-lg font-bold text-yellow-800 mb-2">Menunggu Verifikasi</h4>
                                <p class="text-yellow-700 text-sm">Data pendaftaran Anda sedang diverifikasi aplikasi, silakan tunggu max 1x24 jam.</p>
                            </div>
                        @else
                            <div class="mt-8 bg-blue-50 border border-brand-blue rounded-xl p-6">
                                <h4 class="text-lg font-bold text-brand-black mb-2">Lengkapi Profil Anda</h4>
                                <p class="text-gray-600 mb-6 text-sm">Untuk mulai memposting muatan atau mengambil tawaran muatan (bidding), Anda perlu memverifikasi akun Anda sebagai Merchant atau Driver.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <a href="{{ route('verification') }}" class="block p-5 border border-gray-200 rounded-lg hover:border-brand-blue hover:shadow-md transition bg-white text-center">
                                        <div class="text-brand-blue mb-2">
                                            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <h5 class="font-bold text-gray-900">Daftar sebagai Merchant</h5>
                                        <p class="text-xs text-gray-500 mt-1">Pemilik Muatan (Shipper)</p>
                                    </a>
                                    
                                    <a href="{{ route('driver.verification') }}" class="block p-5 border border-gray-200 rounded-lg hover:border-brand-blue hover:shadow-md transition bg-white text-center">
                                        <div class="text-brand-blue mb-2">
                                            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                        </div>
                                        <h5 class="font-bold text-gray-900">Daftar sebagai Driver</h5>
                                        <p class="text-xs text-gray-500 mt-1">Pemilik Armada (Transporter)</p>
                                    </a>
                                </div>
                            </div>
                        @endif
                    @else
                        @if(($user->hasRole('merchant') || $user->hasRole('driver')) && !$user->is_subscribed)
                            <div class="mt-8 bg-green-50 border border-green-400 rounded-xl p-6">
                                <h4 class="text-lg font-bold text-green-800 mb-2">Pengajuan Akun Diterima!</h4>
                                <p class="text-green-700 text-sm mb-4">Selamat! Pengajuan akun Anda telah disetujui (Acc). Silakan lakukan deposit jaminan untuk membuka aplikasi dan mulai bertransaksi.</p>
                                <a href="{{ route('subscription') }}" class="inline-block px-6 py-2 bg-brand-blue text-white rounded-lg font-bold hover:bg-blue-700 transition shadow">
                                    Lakukan Deposit
                                </a>
                            </div>
                        @else
                            <div class="mt-4 p-4 bg-green-50 text-green-800 rounded border border-green-200">
                                Anda sudah terverifikasi dengan role: <strong>{{ implode(', ', $user->getRoleNames()->toArray()) }}</strong>.
                            </div>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
