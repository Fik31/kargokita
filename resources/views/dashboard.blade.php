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
                    <h3 id="tour-welcome" class="text-xl font-bold mb-2 flex items-center gap-2">
                        Selamat Datang, {{ Auth::user()->name }}!
                        @if(Auth::user()->tier && !Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('hse'))
                            <x-tier-badge :tier="Auth::user()->tier" />
                        @endif
                    </h3>
                    @if(!Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('hse'))
                    <p id="tour-tier" class="mb-4">Saat ini akun Anda berada di tier <strong>{{ strtoupper(Auth::user()->tier ?? 'COMMON') }}</strong>.</p>
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
                            <div id="tour-verification" class="mt-8 bg-blue-50 border border-brand-blue rounded-xl p-6">
                                <h4 class="text-lg font-bold text-brand-black mb-2">Lengkapi Profil Anda</h4>
                                <p class="text-gray-600 mb-6 text-sm">Untuk mulai memposting muatan atau mengambil tawaran muatan (bidding), Anda perlu memverifikasi akun Anda sebagai Merchant atau Driver.</p>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <a id="tour-merchant" href="{{ route('verification') }}" class="block p-5 border border-gray-200 rounded-lg hover:border-brand-blue hover:shadow-md transition bg-white text-center">
                                        <div class="text-brand-blue mb-2">
                                            <svg class="w-10 h-10 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path></svg>
                                        </div>
                                        <h5 class="font-bold text-gray-900">Daftar sebagai Merchant</h5>
                                        <p class="text-xs text-gray-500 mt-1">Pemilik Muatan (Shipper)</p>
                                    </a>
                                    
                                    <a id="tour-driver" href="{{ route('driver.verification') }}" class="block p-5 border border-gray-200 rounded-lg hover:border-brand-blue hover:shadow-md transition bg-white text-center">
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

    @push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const isCommonUser = @json(!$hasRole && !$hasPendingDriver && !$hasPendingMerchant);
            // UNTUK KEPERLUAN PRESENTASI: Kita nonaktifkan pengecekan hasSeenTour agar tour selalu muncul
            // const hasSeenTour = localStorage.getItem('hasSeenTour_common');

            if (isCommonUser) { //  && !hasSeenTour
                const driver = window.driver.js.driver;
                
                    const isMobile = window.innerWidth < 768;
                    
                    let steps = [
                        {
                            element: '#tour-welcome',
                            popover: {
                                title: 'Selamat Datang di KargoKita! 🎉',
                                description: 'Ini adalah halaman Dashboard utama Anda. Mari ikuti panduan singkat ini untuk memulai.',
                                side: "bottom",
                                align: 'start'
                            }
                        },
                        {
                            element: '#tour-tier',
                            popover: {
                                title: 'Status Akun',
                                description: 'Saat ini akun Anda adalah COMMON. Anda perlu melakukan pendaftaran peran (Merchant atau Driver) untuk mulai bertransaksi.',
                                side: "bottom",
                                align: 'start'
                            }
                        },
                        {
                            element: '#tour-verification',
                            popover: {
                                title: 'Pilih Peran Anda',
                                description: 'Anda dapat memilih untuk menjadi Shipper (Pengirim) atau Transporter (Pemilik Armada) sesuai dengan kebutuhan bisnis Anda.',
                                side: "top",
                                align: 'start'
                            }
                        },
                        {
                            element: '#tour-merchant',
                            popover: {
                                title: 'Daftar sebagai Merchant',
                                description: 'Pilih opsi ini jika Anda ingin mencari armada untuk mengirimkan barang muatan Anda.',
                                side: "right",
                                align: 'center'
                            }
                        },
                        {
                            element: '#tour-driver',
                            popover: {
                                title: 'Daftar sebagai Driver',
                                description: 'Pilih opsi ini jika Anda memiliki armada truk dan sedang mencari muatan untuk dikirimkan.',
                                side: "left",
                                align: 'center'
                            }
                        }
                    ];

                    if (!isMobile) {
                        steps.push(
                            {
                                element: '#nav-feed',
                                popover: {
                                    title: 'Feed Utama',
                                    description: 'Menu ini menampilkan informasi dan update terbaru seputar KargoKita.',
                                    side: "right",
                                    align: 'start'
                                }
                            },
                            {
                                element: '#nav-chat',
                                popover: {
                                    title: 'Pesan (Chat)',
                                    description: 'Gunakan fitur ini untuk berkomunikasi dengan pengguna lain terkait pengiriman atau order Anda.',
                                    side: "right",
                                    align: 'start'
                                }
                            },
                            {
                                element: '#nav-bidding',
                                popover: {
                                    title: 'Bursa DO (Bidding)',
                                    description: 'Menu ini adalah tempat Anda mencari dan melakukan penawaran (bidding) pada muatan yang tersedia.',
                                    side: "right",
                                    align: 'start'
                                }
                            },
                            {
                                element: '#nav-profile',
                                popover: {
                                    title: 'Profil & Pengaturan',
                                    description: 'Di sini Anda dapat melihat detail profil, mengajukan verifikasi lanjutan, serta keluar dari aplikasi.',
                                    side: "right",
                                    align: 'start'
                                }
                            }
                        );
                    }

                    const driverObj = driver({
                        showProgress: true,
                        animate: true,
                        doneBtnText: 'Oke, Saya Mengerti',
                        closeBtnText: 'Skip Tutorial',
                        nextBtnText: 'Selanjutnya',
                        prevBtnText: 'Kembali',
                        steps: steps,
                    onDestroyStarted: () => {
                        if (!driverObj.hasNextStep() || confirm("Skip tutorial ini? Anda selalu bisa memulainya nanti.")) {
                            localStorage.setItem('hasSeenTour_common', 'true');
                            driverObj.destroy();
                            document.body.classList.remove('driver-active', 'driver-fix-stacking');
                        }
                    },
                });
                
                window.dispatchEvent(new CustomEvent('close-sidebar'));
                setTimeout(() => {
                    driverObj.drive();
                }, 400);
            }
        });
    </script>
    @endpush
</x-app-layout>
