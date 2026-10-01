<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div id="tour-disputes-header" class="mb-8">
        <h2 class="text-2xl font-bold text-gray-800">Admin Panel: Mediasi & Keadaan Darurat (SOS)</h2>
        <p class="text-gray-500">Laporan fraud, kecurangan kapasitas, dan kerusakan kendaraan di jalan.</p>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
            {{ session('message') }}
        </div>
    @endif

    <div id="tour-disputes-table" class="bg-white shadow overflow-hidden sm:rounded-lg">
        <!-- Desktop Table View -->
        <div class="hidden md:block overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID / Waktu</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pelapor</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Jenis Kendala</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($disputes as $dispute)
                        <tr x-data="{ open: false }">
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">#{{ $dispute->id }}</div>
                                <div class="text-xs text-gray-500">{{ $dispute->created_at->format('d M Y H:i') }}</div>
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ $dispute->reporter->name ?? 'N/A' }}</div>
                                <div class="text-xs text-gray-500">{{ $dispute->reporter->roles->first()->name ?? '' }}</div>
                                @if($dispute->cargoLoad)
                                    @if($dispute->cargoLoad->sla_type === 'Premium')
                                        <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200 shadow-sm">
                                            ⭐ Premium SLA (24/7 Support)
                                        </span>
                                    @elseif($dispute->cargoLoad->sla_type === 'Priority')
                                        <span class="mt-1 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                            ⚡ Priority
                                        </span>
                                    @endif
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-bold text-gray-900">{{ ucfirst(str_replace('_', ' ', $dispute->type)) }}</div>
                                <div class="text-xs text-gray-500">Trip #{{ $dispute->trip_id }} | Load #{{ $dispute->load_id }}</div>
                            </td>
                            <td class="px-6 py-4">
                                @if($dispute->status === 'open')
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Menunggu Mediasi</span>
                                @else
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Diselesaikan</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm font-medium">
                                <button @click="open = !open" class="text-brand-blue hover:text-blue-900">Lihat Detail & Mediasi</button>
    
                                <!-- Modal Detail -->
                                <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>
                                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                                <div class="sm:flex sm:items-start">
                                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                            Detail Laporan Darurat #{{ $dispute->id }}
                                                        </h3>
                                                        <div class="mt-4 text-sm text-gray-500">
                                                            <p><strong>Pelapor:</strong> {{ $dispute->reporter->name ?? 'N/A' }}</p>
                                                            <p><strong>Merchant Tujuan:</strong> {{ $dispute->cargoLoad->merchant->name ?? 'N/A' }} 
                                                                @if($dispute->cargoLoad && $dispute->cargoLoad->sla_type === 'Premium')
                                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-purple-100 text-purple-800">
                                                                        Premium Merchant (Priority Backup Truck)
                                                                    </span>
                                                                @endif
                                                            </p>
                                                            <p><strong>Driver:</strong> {{ $dispute->trip->driver->name ?? 'N/A' }}</p>
                                                            <hr class="my-4">
                                                            <p><strong>Alasan:</strong></p>
                                                            <p class="mt-1 p-3 bg-gray-50 border rounded text-gray-800">{{ $dispute->reason }}</p>
                                                            
                                                            @if($dispute->resolution_notes)
                                                            <p class="mt-4"><strong>Catatan Resolusi / Bukti:</strong></p>
                                                            <p class="mt-1 p-3 bg-blue-50 border border-blue-100 rounded text-blue-800 break-words">{{ $dispute->resolution_notes }}</p>
                                                            @endif
                                                        </div>
    
                                                        @if($dispute->status === 'open')
                                                        <div class="mt-6 border-t pt-4" x-data="{ notes: '' }">
                                                            <label class="block text-sm font-medium text-gray-700">Tindakan Admin / Hasil Mediasi</label>
                                                            <textarea x-model="notes" class="mt-1 w-full border-gray-300 rounded p-2 text-sm focus:ring-brand-blue" rows="3" placeholder="Sebutkan hasil mediasi (misal: merchant disuspend, driver dibebaskan, dsb.)"></textarea>
                                                            <button @click="$wire.resolveDispute({{ $dispute->id }}, notes); open = false" type="button" class="mt-2 w-full bg-brand-blue text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Tandai Telah Dimediasi</button>
                                                        </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                                <button @click="open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                                    Tutup
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-4 text-center text-gray-500">Belum ada laporan darurat/dispute.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Mobile Card View -->
        <div class="block md:hidden border-t border-gray-200 divide-y divide-gray-200">
            @forelse($disputes as $dispute)
                <div x-data="{ open: false }" class="p-4 bg-white hover:bg-gray-50">
                    <div class="flex justify-between items-start mb-3">
                        <div>
                            <div class="text-sm font-bold text-gray-900">#{{ $dispute->id }} - {{ ucfirst(str_replace('_', ' ', $dispute->type)) }}</div>
                            <div class="text-xs text-gray-500">{{ $dispute->created_at->format('d M Y H:i') }}</div>
                        </div>
                        <div>
                            @if($dispute->status === 'open')
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Menunggu Mediasi</span>
                            @else
                                <span class="px-2 py-1 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Diselesaikan</span>
                            @endif
                        </div>
                    </div>
                    <div class="mb-4 text-sm">
                        <div class="text-gray-900"><span class="font-bold">Pelapor:</span> {{ $dispute->reporter->name ?? 'N/A' }}</div>
                        <div class="text-gray-500 mt-1">Trip #{{ $dispute->trip_id }} | Load #{{ $dispute->load_id }}</div>
                        @if($dispute->cargoLoad)
                            @if($dispute->cargoLoad->sla_type === 'Premium')
                                <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-purple-100 text-purple-800 border border-purple-200 shadow-sm">
                                    ⭐ Premium SLA (24/7 Support)
                                </span>
                            @elseif($dispute->cargoLoad->sla_type === 'Priority')
                                <span class="mt-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 border border-blue-200">
                                    ⚡ Priority
                                </span>
                            @endif
                        @endif
                    </div>
                    
                    <button @click="open = !open" class="block w-full text-center text-white bg-blue-600 hover:bg-blue-700 px-4 py-2 rounded shadow text-xs font-bold transition">
                        Lihat Detail & Mediasi
                    </button>

                    <!-- Modal Detail (Mobile) -->
                    <div x-show="open" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" @click="open = false"></div>
                            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                    <div class="sm:flex sm:items-start">
                                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                                Detail Laporan Darurat #{{ $dispute->id }}
                                            </h3>
                                            <div class="mt-4 text-sm text-gray-500">
                                                <p><strong>Pelapor:</strong> {{ $dispute->reporter->name ?? 'N/A' }}</p>
                                                <p><strong>Merchant Tujuan:</strong> {{ $dispute->cargoLoad->merchant->name ?? 'N/A' }} 
                                                    @if($dispute->cargoLoad && $dispute->cargoLoad->sla_type === 'Premium')
                                                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-bold bg-purple-100 text-purple-800">
                                                            Premium Merchant (Priority Backup Truck)
                                                        </span>
                                                    @endif
                                                </p>
                                                <p><strong>Driver:</strong> {{ $dispute->trip->driver->name ?? 'N/A' }}</p>
                                                <hr class="my-4">
                                                <p><strong>Alasan:</strong></p>
                                                <p class="mt-1 p-3 bg-gray-50 border rounded text-gray-800">{{ $dispute->reason }}</p>
                                                
                                                @if($dispute->resolution_notes)
                                                <p class="mt-4"><strong>Catatan Resolusi / Bukti:</strong></p>
                                                <p class="mt-1 p-3 bg-blue-50 border border-blue-100 rounded text-blue-800 break-words">{{ $dispute->resolution_notes }}</p>
                                                @endif
                                            </div>

                                            @if($dispute->status === 'open')
                                            <div class="mt-6 border-t pt-4" x-data="{ notes: '' }">
                                                <label class="block text-sm font-medium text-gray-700">Tindakan Admin / Hasil Mediasi</label>
                                                <textarea x-model="notes" class="mt-1 w-full border-gray-300 rounded p-2 text-sm focus:ring-brand-blue" rows="3" placeholder="Sebutkan hasil mediasi (misal: merchant disuspend, driver dibebaskan, dsb.)"></textarea>
                                                <button @click="$wire.resolveDispute({{ $dispute->id }}, notes); open = false" type="button" class="mt-2 w-full bg-brand-blue text-white font-bold py-2 px-4 rounded hover:bg-blue-700">Tandai Telah Dimediasi</button>
                                            </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                    <button @click="open = false" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                        Tutup
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="p-6 text-center text-gray-500">Belum ada laporan darurat/dispute.</div>
            @endforelse
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.3.1/dist/driver.js.iife.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isHseUser = @json(Auth::user()->hasRole('hse') || Auth::user()->hasRole('administrator'));
        
        if (isHseUser) {
            const driver = window.driver.js.driver;
            const isMobile = window.innerWidth < 768;
            
            let steps = [
                {
                    element: '#tour-disputes-header',
                    popover: {
                        title: 'Darurat & Mediasi',
                        description: 'Ini adalah halaman pusat penanganan insiden darurat, fraud, atau masalah asuransi selama pengiriman berlangsung.',
                        side: "bottom",
                        align: 'start'
                    }
                },
                {
                    element: '#tour-disputes-table',
                    popover: {
                        title: 'Daftar Laporan (Tiket)',
                        description: 'Semua laporan SOS akan masuk ke sini. Anda dapat membuka detailnya, melakukan mediasi, lalu menutup tiket tersebut.',
                        side: "top",
                        align: 'center'
                    }
                }
            ];

            if (!isMobile) {
                steps.push({
                    element: '#nav-profile',
                    popover: {
                        title: 'Selesai!',
                        description: 'Itulah menu-menu yang dapat Anda akses sebagai HSE. Terakhir, Anda bisa membuka Profil untuk melakukan pengaturan lanjutan.',
                        side: "right",
                        align: 'start'
                    }
                });
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
