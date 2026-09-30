<div class="max-w-4xl mx-auto py-12 px-4 sm:px-6 lg:px-8">
    <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">Deposit Jaminan Keanggotaan</h2>
        <p class="mt-4 text-lg text-gray-500">Deposit 1 kali di awal pendaftaran. Jika tidak mendapatkan order (untuk Driver) atau tidak mendapatkan armada (untuk Merchant) dalam 1 tahun, dana Anda 100% dikembalikan!</p>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200 shadow-sm text-center">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-6 text-sm text-blue-800 rounded-xl bg-blue-50 border border-blue-200 shadow-sm text-center">
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white rounded-3xl shadow-xl overflow-hidden border border-gray-100 flex flex-col md:flex-row">
        
        <!-- Left Side: Pricing & Benefits -->
        <div class="bg-gradient-to-br from-brand-blue to-blue-900 text-white p-8 md:w-1/2 flex flex-col justify-center">
            <h3 class="text-2xl font-bold mb-2">Deposit Wajib (Refundable)</h3>
            <p class="text-blue-200 mb-6">Wajib bagi semua Driver dan Merchant. Menjamin keseriusan Anda di ekosistem Kargokita</p>
            
            <div class="mb-8">
                <span class="text-5xl font-extrabold">Rp 1.000.000</span>
                <span class="text-xl text-blue-200"></span>
            </div>
            
            <ul class="space-y-4 mb-8">
                <li class="flex items-center">
                    <svg class="h-6 w-6 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Akses Fitur Rating antar Pengguna
                </li>
                <li class="flex items-center">
                    <svg class="h-6 w-6 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                    Komisi Referral / Agen Langsung
                </li>
                @if(Auth::user()->hasRole('driver'))
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Fitur Sosmed & Info Laka Eksklusif
                    </li>
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Akses Bidding LTL & Prioritas Muatan
                    </li>
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Klaim Biaya Branding (Stiker & Pajak)
                    </li>
                @else
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Komisi 10% Member-get-Member
                    </li>
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Dedicated Driver Recommendation
                    </li>
                    <li class="flex items-center">
                        <svg class="h-6 w-6 text-yellow-400 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Prioritas masuk ke Top Bid & Pasang Iklan
                    </li>
                @endif
            </ul>
        </div>

        <!-- Right Side: Action -->
        <div class="p-8 md:w-1/2 flex flex-col justify-center bg-gray-50">
            @if($hasActiveSubscription)
                <div class="text-center">
                    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-green-100 text-green-600 mb-4">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-xl font-bold text-gray-900 mb-2">Deposit Aktif</h4>
                    <p class="text-gray-500 mb-6">Status Anda saat ini adalah Member Resmi. Anda telah menyetor deposit dan bisa menikmati seluruh fitur aplikasi.</p>
                </div>
            @else
                <div class="text-center">
                    <h4 class="text-xl font-bold text-gray-900 mb-4">Lakukan Pembayaran Deposit</h4>
                    <p class="text-gray-500 mb-6 text-sm">Dana Anda aman bersama kami. Garansi 100% uang kembali jika selama 1 tahun berturut-turut Anda tidak mendapatkan transaksi di platform ini.</p>
                    
                    <button wire:click="processPayment" class="w-full bg-brand-blue hover:bg-blue-700 text-white font-bold py-4 px-8 rounded-xl shadow-lg transition-transform transform hover:scale-105">
                        Bayar Deposit (Simulasi)
                    </button>
                    <p class="text-[10px] text-gray-400 mt-4">*Syarat dan ketentuan berlaku terkait kebijakan refund.</p>
                </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isMerchantUser = @json(Auth::user()->hasRole('merchant'));
        const isDriverUser = @json(Auth::user()->hasRole('driver'));
        
        if (isMerchantUser || isDriverUser) {
            const driver = window.driver.js.driver;
            
            const driverObj = driver({
                showProgress: true,
                animate: true,
                doneBtnText: 'Oke, Saya Mengerti',
                closeBtnText: 'Skip Tutorial',
                nextBtnText: 'Selanjutnya',
                prevBtnText: 'Kembali',
                steps: [
                    {
                        element: '.max-w-4xl > div:first-child',
                        popover: {
                            title: 'Deposit Jaminan Keanggotaan',
                            description: 'Ini adalah halaman untuk melakukan Deposit Jaminan. Dana Anda aman karena bersifat Refundable (dapat dikembalikan) sesuai syarat dan ketentuan.',
                            side: "bottom",
                            align: 'center'
                        }
                    },
                    {
                        element: '.bg-gradient-to-br',
                        popover: {
                            title: 'Keuntungan Deposit',
                            description: 'Dengan melakukan deposit, Anda akan membuka akses penuh ke seluruh fitur eksklusif KargoKita, termasuk fitur Rating dan prioritas muatan.',
                            side: "right",
                            align: 'center'
                        }
                    },
                    {
                        element: '.bg-gray-50.p-8',
                        popover: {
                            title: 'Status & Pembayaran',
                            description: 'Di sini Anda dapat melihat status deposit Anda saat ini atau memproses simulasi pembayaran jika belum aktif.',
                            side: "left",
                            align: 'center'
                        }
                    }
                ],
                onDestroyStarted: () => {
                    if (!driverObj.hasNextStep() || confirm("Skip tutorial ini?")) {
                        driverObj.destroy();
                    }
                },
            });
            
            setTimeout(() => {
                driverObj.drive();
            }, 500);
        }
    });
</script>
@endpush
