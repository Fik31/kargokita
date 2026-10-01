<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 mb-6">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 sm:gap-0 mb-6">
            <h2 id="tour-history-header" class="text-2xl font-bold text-gray-900">Riwayat Perjalanan (History)</h2>
            <div class="w-full sm:w-64">
                <input type="text" wire:model.live="search" placeholder="Cari merchant atau driver..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm">
            </div>
        </div>

        @if (session()->has('error'))
            <div class="p-4 mb-4 text-sm text-blue-800 rounded-xl bg-blue-50 border border-blue-200">
                {{ session('error') }}
            </div>
        @endif
        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-xl bg-green-50 border border-green-200">
                {{ session('message') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table id="tour-history-table" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">No. Trip</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Rute</th>
                        @if(Auth::user()->role !== 'driver')
                            <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Driver</th>
                        @endif
                        <th scope="col" class="px-6 py-3 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Waktu Selesai</th>
                        <th id="tour-history-event" scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Total Event</th>
                        <th id="tour-history-foto" scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Foto</th>
                        <th scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th id="tour-history-aksi" scope="col" class="px-6 py-3 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @forelse($trips as $trip)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-brand-blue">TRK-{{ str_pad($trip->id, 3, '0', STR_PAD_LEFT) }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900">{{ $trip->cargo->origin_name ?? 'Jakarta' }} &rarr; {{ $trip->cargo->dest_name ?? 'Surabaya' }}</div>
                            </td>
                            @if(Auth::user()->role !== 'driver')
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-8 w-8 rounded-full bg-gray-200 flex items-center justify-center text-xs font-bold text-gray-600">
                                            {{ substr($trip->driver->name ?? 'D', 0, 1) }}
                                        </div>
                                        <div class="ml-3">
                                            <div class="text-sm font-medium text-gray-900">{{ $trip->driver->name ?? 'Unknown' }}</div>
                                        </div>
                                    </div>
                                </td>
                            @endif
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $trip->updated_at->format('d M Y, H:i WIB') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="viewEvents({{ $trip->id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded text-brand-blue bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue transition">
                                    {{ $trip->events->count() }} Event
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <button wire:click="viewPhotos({{ $trip->id }})" class="inline-flex items-center px-3 py-1 border border-transparent text-xs leading-4 font-medium rounded text-blue-700 bg-blue-100 hover:bg-blue-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition">
                                    {{ $trip->photos->count() }} Foto
                                </button>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    Selesai
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <div class="flex flex-col gap-2">
                                    <a href="{{ route('waybill', ['trip_id' => $trip->id]) }}" target="_blank" class="text-xs font-medium bg-gray-100 text-gray-700 py-1 px-2 rounded border border-gray-200 hover:bg-gray-200 transition">
                                        Surat Jalan
                                    </a>
                                    @if(!\App\Models\Rating::where('trip_id', $trip->id)->where('rater_id', Auth::id())->exists())
                                        <button wire:click="openRatingModal({{ $trip->id }}, {{ Auth::user()->hasRole('driver') ? $trip->cargo->merchant_id : $trip->driver_id }})" class="text-sm font-medium text-brand-blue hover:text-blue-700 hover:underline">
                                            Beri Penilaian
                                        </button>
                                    @else
                                        <span class="text-xs text-gray-500">Sudah dinilai</span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ Auth::user()->role !== 'driver' ? '6' : '5' }}" class="px-6 py-8 text-center text-gray-500">
                                Belum ada riwayat perjalanan yang selesai.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Modal Event Detail -->
    @if($selectedTripId)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal" aria-hidden="true"></div>

            <!-- This element is to trick the browser into centering the modal contents. -->
            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <div class="sm:flex sm:items-start">
                        <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Detail Event - TRK-{{ str_pad($selectedTripId, 3, '0', STR_PAD_LEFT) }}
                            </h3>
                            <div class="mt-4 space-y-4">
                                @forelse($selectedEvents as $event)
                                    <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                        <div class="flex justify-between items-center mb-2">
                                            <span class="font-bold text-gray-700">
                                                @if($event->type === 'dwell')
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800 mr-2">Pemberhentian</span>
                                                @else
                                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 mr-2">Deviasi</span>
                                                @endif
                                                {{ $event->location_name ?? 'Lokasi Auto-detected' }}
                                            </span>
                                            <span class="text-sm text-gray-500">{{ $event->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                        <div class="text-sm text-gray-600">
                                            <p><strong>Durasi:</strong> {{ $event->duration_minutes }} Menit</p>
                                            <p><strong>Catatan:</strong> {{ $event->notes ?? '-' }}</p>
                                        </div>
                                    </div>
                                @empty
                                    <p class="text-sm text-gray-500 italic">Tidak ada catatan event untuk trip ini.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="closeModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand-blue text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Photo Detail -->
    @if($showPhotoModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                        Galeri Foto - TRK-{{ str_pad($selectedTripId, 3, '0', STR_PAD_LEFT) }}
                    </h3>
                    
                    @if(count($selectedPhotos) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                            @foreach($selectedPhotos as $photo)
                                <div class="border border-gray-200 rounded-lg overflow-hidden shadow-sm">
                                    <div class="h-48 bg-gray-100 relative">
                                        <img src="{{ Storage::url($photo->path) }}" alt="Foto {{ $photo->type }}" class="w-full h-full object-cover">
                                        <div class="absolute top-2 right-2 bg-black bg-opacity-60 text-white text-xs px-2 py-1 rounded">
                                            {{ ucfirst($photo->type) }}
                                        </div>
                                    </div>
                                    <div class="p-2 text-xs text-gray-500 bg-gray-50">
                                        Diunggah: {{ $photo->created_at->format('d M Y, H:i') }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-8 text-gray-500">
                            Tidak ada foto untuk perjalanan ini.
                        </div>
                    @endif
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" wire:click="closeModal" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand-blue text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue sm:ml-3 sm:w-auto sm:text-sm">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Modal Rating -->
    @if($showRatingModal)
    <div class="fixed inset-0 z-50 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" wire:click="closeModal" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-md sm:w-full">
                <form wire:submit.prevent="submitRating">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            Beri Penilaian
                        </h3>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Skor Bintang (1-5)</label>
                            <select wire:model="ratingScore" class="w-full border-gray-300 rounded-md shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue focus:ring-opacity-50">
                                <option value="5">⭐⭐⭐⭐⭐ (5) Sangat Baik</option>
                                <option value="4">⭐⭐⭐⭐ (4) Baik</option>
                                <option value="3">⭐⭐⭐ (3) Cukup</option>
                                <option value="2">⭐⭐ (2) Kurang</option>
                                <option value="1">⭐ (1) Buruk</option>
                            </select>
                            @error('ratingScore') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Ulasan (Opsional)</label>
                            <textarea wire:model="ratingReview" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue focus:ring-opacity-50" placeholder="Bagaimana pengalaman Anda?"></textarea>
                            @error('ratingReview') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-brand-blue text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-brand-blue sm:ml-3 sm:w-auto sm:text-sm">
                            Kirim Penilaian
                        </button>
                        <button type="button" wire:click="closeModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                            Batal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    @endif
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
                        element: '#tour-history-header',
                        popover: {
                            title: 'Riwayat Perjalanan',
                            description: 'Di sini Anda dapat melihat seluruh riwayat perjalanan (trip) yang telah selesai.',
                            side: "bottom",
                            align: 'start'
                        }
                    },
                    {
                        element: '#tour-history-event',
                        popover: {
                            title: 'Total Event',
                            description: 'Angka ini menunjukkan jumlah aktivitas atau kejadian selama trip (seperti Check-in Berhenti atau Deviasi rute). Anda bisa <b>mengklik angkanya</b> untuk melihat daftar lengkap event dan waktu kejadiannya.',
                            side: "bottom",
                            align: 'center'
                        }
                    },
                    {
                        element: '#tour-history-foto',
                        popover: {
                            title: 'Galeri Foto',
                            description: 'Menampilkan jumlah foto bukti yang Anda unggah selama proses muat dan bongkar. Klik tombolnya untuk melihat hasil foto beserta watermark GPS-nya.',
                            side: "bottom",
                            align: 'center'
                        }
                    },
                    {
                        element: '#tour-history-aksi',
                        popover: {
                            title: 'Aksi & Dokumen',
                            description: 'Di kolom ini, Anda bisa mengunduh <b>Surat Jalan</b> yang sudah ditandatangani. Jangan lupa untuk <b>Beri Penilaian (Rating)</b> kepada Merchant atas pengalaman kerja sama ini.',
                            side: "bottom",
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
