<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 mb-6">
        <h2 id="tour-report-header" class="text-2xl font-bold text-gray-900">Laporan & Analitik</h2>
        <div class="flex flex-col sm:flex-row gap-2 w-full sm:w-auto">
            <select class="w-full sm:w-auto bg-white border border-gray-300 text-gray-700 rounded-lg px-3 py-2 text-sm shadow-sm focus:ring-brand-blue focus:border-brand-blue">
                <option>Bulan Ini</option>
                <option>Bulan Lalu</option>
                <option>Tahun Ini</option>
            </select>
            <button class="w-full sm:w-auto bg-gray-100 hover:bg-gray-200 text-gray-800 font-bold py-2 px-4 rounded-lg shadow-sm border border-gray-300 transition flex items-center justify-center text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Export PDF
            </button>
        </div>
    </div>

    <!-- Stats Cards -->
    <div id="tour-report-stats" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Total Transaksi</p>
                <h3 class="text-2xl font-bold text-gray-900">142</h3>
                <p class="text-xs text-green-600 mt-2 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    +12% vs Bulan Lalu
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-brand-blue">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c-1.105 0-2-.895-2-2V7c0-1.105.895-2 2-2h12c1.105 0 2 .895 2 2v10c0 1.105-.895 2-2 2H9z"></path></svg>
            </div>
        </div>
        
        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Total Pendapatan</p>
                <h3 class="text-xl font-bold text-gray-900">Rp 128.5M</h3>
                <p class="text-xs text-green-600 mt-2 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                    +8% vs Bulan Lalu
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-green-50 flex items-center justify-center text-green-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Persentase Sukses</p>
                <h3 class="text-2xl font-bold text-gray-900">98.5%</h3>
                <p class="text-xs text-gray-500 mt-2 flex items-center">
                    Berdasarkan SLA
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-blue-50 flex items-center justify-center text-blue-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white rounded-2xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
            <div>
                <p class="text-sm text-gray-500 font-medium mb-1">Trip Aktif</p>
                <h3 class="text-2xl font-bold text-gray-900">12</h3>
                <p class="text-xs text-brand-blue mt-2 flex items-center">
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    2 Perlu Perhatian
                </p>
            </div>
            <div class="w-12 h-12 rounded-full bg-yellow-50 flex items-center justify-center text-yellow-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Charts Dummy -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 text-lg">Grafik Transaksi Harian</h3>
            <div class="flex items-end justify-between h-48 space-x-2 pt-4">
                <div class="w-full bg-brand-blue rounded-t-sm h-12 hover:opacity-80 transition" title="Senin"></div>
                <div class="w-full bg-brand-blue rounded-t-sm h-24 hover:opacity-80 transition" title="Selasa"></div>
                <div class="w-full bg-brand-blue rounded-t-sm h-16 hover:opacity-80 transition" title="Rabu"></div>
                <div class="w-full bg-brand-blue rounded-t-sm h-32 hover:opacity-80 transition" title="Kamis"></div>
                <div class="w-full bg-brand-blue rounded-t-sm h-20 hover:opacity-80 transition" title="Jumat"></div>
                <div class="w-full bg-brand-blue rounded-t-sm h-40 hover:opacity-80 transition" title="Sabtu"></div>
                <div class="w-full bg-brand-blue rounded-t-sm h-28 hover:opacity-80 transition" title="Minggu"></div>
            </div>
            <div class="flex justify-between text-xs text-gray-500 mt-2">
                <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
            </div>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
            <h3 class="font-bold text-gray-800 mb-4 text-lg">Distribusi Jenis Layanan</h3>
            <div class="flex items-center justify-center h-48">
                <!-- CSS Pie Chart Trick -->
                <div class="relative w-40 h-40 rounded-full" style="background: conic-gradient(#e60000 0% 70%, #3b82f6 70% 100%);">
                    <div class="absolute inset-4 bg-white rounded-full flex items-center justify-center flex-col">
                        <span class="text-xs text-gray-500">Total</span>
                        <span class="font-bold text-lg text-gray-900">142</span>
                    </div>
                </div>
            </div>
            <div class="flex justify-center gap-6 mt-4 text-sm text-gray-600">
                <div class="flex items-center gap-2"><div class="w-3 h-3 bg-brand-blue rounded-full"></div> FTL (70%)</div>
                <div class="flex items-center gap-2"><div class="w-3 h-3 bg-blue-500 rounded-full"></div> LTL (30%)</div>
            </div>
        </div>
    </div>
    
    <div id="tour-report-history" class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
        <h3 class="font-bold text-gray-800 mb-4 text-lg">Riwayat Transaksi Terbaru</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left text-gray-500">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3">No. Transaksi</th>
                        <th scope="col" class="px-6 py-3">Tanggal</th>
                        <th scope="col" class="px-6 py-3">Jenis</th>
                        <th scope="col" class="px-6 py-3">Total (Rp)</th>
                        <th scope="col" class="px-6 py-3 text-right">Status</th>
                    </tr>
                </thead>
                <tbody>
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4 font-bold text-gray-900">TRX-00123</td>
                        <td class="px-6 py-4">12 Okt 2026</td>
                        <td class="px-6 py-4">LTL</td>
                        <td class="px-6 py-4">Rp 450.000</td>
                        <td class="px-6 py-4 text-right"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Selesai</span></td>
                    </tr>
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4 font-bold text-gray-900">TRX-00124</td>
                        <td class="px-6 py-4">12 Okt 2026</td>
                        <td class="px-6 py-4">FTL</td>
                        <td class="px-6 py-4">Rp 2.150.000</td>
                        <td class="px-6 py-4 text-right"><span class="bg-yellow-100 text-yellow-800 px-2 py-1 rounded text-xs font-bold">Dalam Proses</span></td>
                    </tr>
                    <tr class="bg-white border-b">
                        <td class="px-6 py-4 font-bold text-gray-900">TRX-00125</td>
                        <td class="px-6 py-4">11 Okt 2026</td>
                        <td class="px-6 py-4">FTL</td>
                        <td class="px-6 py-4">Rp 1.800.000</td>
                        <td class="px-6 py-4 text-right"><span class="bg-green-100 text-green-800 px-2 py-1 rounded text-xs font-bold">Selesai</span></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js"></script>
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
                        element: '#tour-report-header',
                        popover: {
                            title: 'Laporan & Wallet',
                            description: 'Di sini Anda dapat melihat statistik penghasilan dan performa pengiriman Anda.',
                            side: "bottom",
                            align: 'start'
                        }
                    },
                    {
                        element: '#tour-report-stats',
                        popover: {
                            title: 'Statistik Utama',
                            description: 'Ringkasan total transaksi, pendapatan (wallet), serta SLA persentase sukses Anda sebagai driver.',
                            side: "bottom",
                            align: 'center'
                        }
                    },
                    {
                        element: '#tour-report-history',
                        popover: {
                            title: 'Riwayat Transaksi',
                            description: 'Daftar detail transaksi dan pembayaran yang masuk ke saldo wallet Anda.',
                            side: "top",
                            align: 'center'
                        }
                    }
                ],
                onDestroyStarted: () => {
                    if (!driverObj.hasNextStep() || confirm("Skip tutorial ini?")) {
                        driverObj.destroy();
                            document.body.classList.remove('driver-active', 'driver-fix-stacking');
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
