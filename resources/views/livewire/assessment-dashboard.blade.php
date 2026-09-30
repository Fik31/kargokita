<div class="max-w-7xl mx-auto p-4 sm:p-6 lg:p-8">
    <div id="tour-hse-header" class="bg-blue-900 text-white p-4 rounded-t-lg text-center shadow">
        <h1 class="text-2xl font-bold">HSE DRIVER & VEHICLE SAFETY DASHBOARD</h1>
        <p class="text-sm mt-1 text-blue-100">Dashboard monitoring untuk assessment & penentuan tier Driver.</p>
    </div>

    <!-- Main KPI Cards -->
    <div id="tour-hse-kpi" class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4 mt-6">
        <div class="bg-white rounded-lg shadow border-t-4 border-blue-600 overflow-hidden text-center">
            <div class="bg-blue-600 text-white py-2 text-xs font-semibold">TOTAL ASSESSMENT</div>
            <div class="p-4 text-3xl font-bold text-gray-800">{{ $totalAssessments }}</div>
        </div>
        <div class="bg-white rounded-lg shadow border-t-4 border-green-600 overflow-hidden text-center">
            <div class="bg-green-600 text-white py-2 text-xs font-semibold">AVG SCORE</div>
            <div class="p-4 text-3xl font-bold text-gray-800">{{ $avgScore }}%</div>
        </div>
        <div class="bg-white rounded-lg shadow border-t-4 border-yellow-500 overflow-hidden text-center">
            <div class="bg-yellow-500 text-white py-2 text-xs font-semibold">GOLD</div>
            <div class="p-4 text-3xl font-bold text-gray-800">{{ $goldCount }}</div>
        </div>
        <div class="bg-white rounded-lg shadow border-t-4 border-gray-400 overflow-hidden text-center">
            <div class="bg-gray-400 text-white py-2 text-xs font-semibold">SILVER</div>
            <div class="p-4 text-3xl font-bold text-gray-800">{{ $silverCount }}</div>
        </div>
        <div class="bg-white rounded-lg shadow border-t-4 border-orange-400 overflow-hidden text-center col-span-2 md:col-span-1">
            <div class="bg-orange-400 text-white py-2 text-xs font-semibold">BRONZE</div>
            <div class="p-4 text-3xl font-bold text-gray-800">{{ $bronzeCount }}</div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-6">
        <!-- Tables and Chart -->
        <div class="col-span-1 space-y-6">
            <!-- Level Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-blue-900 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">LEVEL</th>
                            <th class="px-4 py-2 text-center font-semibold">JUMLAH</th>
                            <th class="px-4 py-2 text-center font-semibold">PROPORSI</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 font-medium">GOLD</td>
                            <td class="px-4 py-3 text-center">{{ $goldCount }}</td>
                            <td class="px-4 py-3 text-center">{{ $totalAssessments > 0 ? round(($goldCount/$totalAssessments)*100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium">SILVER</td>
                            <td class="px-4 py-3 text-center">{{ $silverCount }}</td>
                            <td class="px-4 py-3 text-center">{{ $totalAssessments > 0 ? round(($silverCount/$totalAssessments)*100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium">BRONZE</td>
                            <td class="px-4 py-3 text-center">{{ $bronzeCount }}</td>
                            <td class="px-4 py-3 text-center">{{ $totalAssessments > 0 ? round(($bronzeCount/$totalAssessments)*100, 1) : 0 }}%</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-red-600">NOT ELIGIBLE</td>
                            <td class="px-4 py-3 text-center">{{ $notEligibleCount }}</td>
                            <td class="px-4 py-3 text-center">{{ $totalAssessments > 0 ? round(($notEligibleCount/$totalAssessments)*100, 1) : 0 }}%</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            
            <!-- CA Table -->
            <div class="bg-white shadow rounded-lg overflow-hidden border border-red-200">
                <table class="min-w-full divide-y divide-gray-200 text-sm">
                    <thead class="bg-red-700 text-white">
                        <tr>
                            <th class="px-4 py-2 text-left font-semibold">CORRECTIVE ACTION</th>
                            <th class="px-4 py-2 text-center font-semibold">JUMLAH</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        <tr>
                            <td class="px-4 py-3 font-medium text-red-600">Open</td>
                            <td class="px-4 py-3 text-center text-red-600 font-bold">{{ $caOpen }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-yellow-600">In Progress</td>
                            <td class="px-4 py-3 text-center text-yellow-600 font-bold">{{ $caInProgress }}</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-3 font-medium text-green-600">Closed</td>
                            <td class="px-4 py-3 text-center text-green-600 font-bold">{{ $caClosed }}</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="col-span-1 lg:col-span-2 space-y-6">
            <!-- Rules / Eligibility info -->
            <div id="tour-hse-rules" class="bg-white p-6 shadow rounded-lg border border-blue-100">
                <h3 class="text-lg font-bold text-blue-900 mb-3 border-b pb-2">PRINSIP ELIGIBILITY & TIERING</h3>
                <ol class="list-decimal list-inside space-y-2 text-sm text-gray-700">
                    <li>Kriteria berlabel <span class="bg-red-100 text-red-800 px-1 rounded font-bold text-xs">Mandatory YES</span> jika bernilai NON-COMPLY = otomatis <b>NOT ELIGIBLE</b> (Downgrade Tier).</li>
                    <li>Status N/A (Tidak Berlaku) akan dikeluarkan dari perhitungan pembagi persentase akhir.</li>
                    <li>Tier ditentukan oleh persentase akhir: <b>Gold &ge; 90%</b>; <b>Silver &ge; 80%</b>; <b>Bronze &ge; 70%</b>.</li>
                    <li>Temuan (NON-COMPLY) wajib diterbitkan <i>Corrective Action</i> dengan PIC yang jelas.</li>
                    <li>Skor Assessment akan langsung tersinkronisasi (*sync*) dengan Tier (Badge) milik akun Driver.</li>
                </ol>
            </div>
            
            <!-- Dummy Chart placeholder (in a real app, use Alpine + Chart.js) -->
            <div class="bg-white p-6 shadow rounded-lg border border-gray-100 h-64 flex flex-col items-center justify-center">
                <div class="text-gray-400 font-medium mb-4">Distribusi Level Assessment (Grafik)</div>
                <div class="flex items-end gap-6 h-32 w-full justify-center border-b border-gray-200 pb-2">
                    <!-- Fake Bars -->
                    <div class="w-12 bg-yellow-500 rounded-t" style="height: {{ $totalAssessments ? ($goldCount/$totalAssessments)*100 : 0 }}%"></div>
                    <div class="w-12 bg-gray-400 rounded-t" style="height: {{ $totalAssessments ? ($silverCount/$totalAssessments)*100 : 0 }}%"></div>
                    <div class="w-12 bg-orange-400 rounded-t" style="height: {{ $totalAssessments ? ($bronzeCount/$totalAssessments)*100 : 0 }}%"></div>
                    <div class="w-12 bg-red-500 rounded-t" style="height: {{ $totalAssessments ? ($notEligibleCount/$totalAssessments)*100 : 0 }}%"></div>
                </div>
                <div class="flex gap-6 mt-2 text-xs text-gray-500 w-full justify-center">
                    <div class="w-12 text-center">GOLD</div>
                    <div class="w-12 text-center">SILVER</div>
                    <div class="w-12 text-center">BRONZE</div>
                    <div class="w-12 text-center">FAIL</div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isHseUser = @json(Auth::user()->hasRole('hse') || Auth::user()->hasRole('administrator'));
        
        if (isHseUser) {
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
                        element: '#tour-hse-header',
                        popover: {
                            title: 'Dashboard HSE',
                            description: 'Selamat datang di Dashboard HSE. Halaman ini memberikan ringkasan status keselamatan dan verifikasi armada.',
                            side: "bottom",
                            align: 'start'
                        }
                    },
                    {
                        element: '#tour-hse-kpi',
                        popover: {
                            title: 'Metrik Utama (KPI)',
                            description: 'Di sini Anda dapat memantau jumlah total asesmen, skor rata-rata, dan distribusi Tier (Gold, Silver, Bronze) yang telah diberikan.',
                            side: "bottom",
                            align: 'center'
                        }
                    },
                    {
                        element: '#tour-hse-rules',
                        popover: {
                            title: 'Prinsip Penilaian',
                            description: 'Sebagai pengingat, ini adalah prinsip dasar penentuan kelayakan (*eligibility*) dan tiering yang Anda gunakan saat verifikasi.',
                            side: "top",
                            align: 'start'
                        }
                    },
                    {
                        element: '#nav-verification',
                        popover: {
                            title: 'Verifikasi HSE',
                            description: 'Anda dapat masuk ke menu ini untuk mulai memverifikasi data dan kendaraan driver.',
                            side: "right",
                            align: 'start'
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
