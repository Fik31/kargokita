<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8">
        <h2 class="text-2xl font-bold text-gray-900">Dompet & Komisi</h2>
        <p class="text-sm text-gray-500">Kelola saldo komisi referral dan penarikan dana Anda.</p>
    </div>

    <!-- Escrow & Cargo Fee Info Banner -->
    <div class="mb-8 bg-blue-50 border-l-4 border-brand-blue p-4 rounded-r-lg shadow-sm">
        <div class="flex items-start">
            <div class="flex-shrink-0 mt-0.5">
                <svg class="h-5 w-5 text-brand-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <div class="ml-3">
                <h3 class="text-sm font-bold text-brand-blue">Informasi Cargo Fee (Escrow / Rekening Bersama)</h3>
                <p class="mt-1 text-sm text-blue-800">
                    Mohon diperhatikan bahwa <strong>Saldo Aktif Dompet</strong> di bawah ini digunakan HANYA untuk deposit langganan dan komisi referral. 
                    Dana pembayaran ongkos kirim (Cargo Fee) yang ditransfer dari Merchant akan diamankan sementara di rekening pusat Administrator (Escrow).
                    <br><br>
                    Untuk <strong>Driver</strong>: Dana ongkos kirim baru akan ditransfer ke rekening pribadi Anda oleh Administrator setelah Trip selesai (POD tervalidasi) dan status Escrow menjadi <em>Released</em>.
                </p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
        <!-- Balance Card -->
        <div class="md:col-span-1 bg-gradient-to-br from-brand-blue to-blue-900 rounded-3xl p-6 text-white shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 p-4 opacity-50">
                <svg class="w-24 h-24 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <div class="relative z-10">
                <p class="text-blue-200 text-sm font-medium mb-1">Total Saldo Aktif</p>
                <h3 class="text-4xl font-extrabold mb-6">Rp {{ number_format($wallet->balance ?? 0, 0, ',', '.') }}</h3>
                
                <button class="w-full bg-white text-brand-blue hover:bg-gray-50 font-bold py-3 px-4 rounded-xl shadow transition">
                    Tarik Dana (Withdraw)
                </button>
            </div>
        </div>

        <!-- Referral Info -->
        <div class="md:col-span-2 bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex flex-col justify-center">
            <h4 class="text-lg font-bold text-gray-900 mb-2">Program Referral Ekosistem</h4>
            <p class="text-sm text-gray-600 mb-6">Ajak rekan Anda bergabung ke ekosistem Kargokita menggunakan kode referral Anda. Dapatkan komisi langsung ke dompet Anda saat mereka menyelesaikan Deposit Jaminan!</p>
            
            <div class="bg-gray-50 p-4 rounded-xl border border-gray-200 flex items-center justify-between">
                <div>
                    <p class="text-xs text-gray-500 mb-1">Kode Referral Anda</p>
                    <p class="text-xl font-bold text-brand-black tracking-widest">{{ Auth::user()->referral_code ?? 'BELUM_ADA' }}</p>
                </div>
                <button class="text-brand-blue font-medium text-sm hover:underline" onclick="navigator.clipboard.writeText('{{ Auth::user()->referral_code }}'); alert('Kode disalin!');">
                    Salin Kode
                </button>
            </div>
        </div>
    </div>

    <!-- Transaction History -->
    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 border-b border-gray-100 bg-gray-50 flex justify-between items-center">
            <h3 class="text-lg font-bold text-gray-900">Riwayat Transaksi</h3>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($wallet->transactions()->latest()->get() as $trx)
                <div class="p-6 flex items-center justify-between hover:bg-gray-50 transition">
                    <div class="flex items-center">
                        <div class="w-12 h-12 rounded-full flex items-center justify-center mr-4 {{ $trx->type === 'credit' ? 'bg-green-100 text-green-600' : 'bg-blue-100 text-blue-600' }}">
                            @if($trx->type === 'credit')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-sm font-bold text-gray-900">{{ $trx->description }}</p>
                            <p class="text-xs text-gray-500">{{ $trx->created_at->format('d M Y, H:i') }} • ID: TRX-{{ str_pad($trx->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-lg font-bold {{ $trx->type === 'credit' ? 'text-green-600' : 'text-blue-600' }}">
                            {{ $trx->type === 'credit' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            @empty
                <div class="p-8 text-center text-gray-500">
                    Belum ada riwayat transaksi.
                </div>
            @endforelse
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isDriverUser = @json(Auth::user()->hasRole('driver'));
        const isMerchantUser = @json(Auth::user()->hasRole('merchant'));
        
        if (isDriverUser || isMerchantUser) {
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
                        element: '.mb-8.bg-blue-50',
                        popover: {
                            title: 'Sistem Escrow / Rekening Bersama',
                            description: 'Mohon baca informasi penting ini. Cargo Fee akan diamankan di pusat (Escrow) dan baru dicairkan setelah trip selesai untuk keamanan transaksi bersama.',
                            side: "bottom",
                            align: 'center'
                        }
                    },
                    {
                        element: '.bg-gradient-to-br',
                        popover: {
                            title: 'Saldo Aktif Dompet',
                            description: 'Ini adalah saldo aktif Anda yang dapat digunakan untuk deposit atau ditarik ke rekening pribadi Anda.',
                            side: "right",
                            align: 'center'
                        }
                    },
                    {
                        element: '.md\\:col-span-2',
                        popover: {
                            title: 'Program Referral',
                            description: 'Bagikan kode referral Anda ke rekan lain dan dapatkan komisi tambahan saat mereka bergabung dan bertransaksi di KargoKita!',
                            side: "left",
                            align: 'center'
                        }
                    },
                    {
                        element: '.divide-y',
                        popover: {
                            title: 'Riwayat Transaksi',
                            description: 'Semua mutasi saldo (masuk/keluar) akan tercatat di sini secara transparan.',
                            side: "top",
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
