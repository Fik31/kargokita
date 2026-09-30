<div class="min-h-screen bg-gray-50 -mt-8 py-8 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-brand-blue rounded-full flex items-center justify-center font-bold text-white text-xl">LP</div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800 leading-tight">Lion Parcel Driver Cockpit</h2>
                    <p class="text-xs text-green-600 flex items-center"><span class="w-2 h-2 rounded-full bg-green-500 mr-1 animate-pulse"></span> GPS Online • Telematics Aktif</p>
                </div>
            </div>
            <div class="bg-blue-50 text-brand-blue font-bold px-3 py-1 rounded-full text-xs border border-blue-100">
                TRK-{{ str_pad($activeTrip->id ?? 1, 3, '0', STR_PAD_LEFT) }} (B 9482 UXZ)
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-100 border border-green-200 text-center font-bold">
                {{ session('message') }}
            </div>
        @endif

        @if($activeTrip)
            <!-- Stepper -->
            <div class="flex bg-white rounded-2xl p-2 mb-6 border border-gray-200 shadow-sm">
                <div class="flex-1 text-center py-2 rounded-xl {{ $activeTrip->status === 'loading' ? 'bg-brand-blue text-white' : 'text-gray-500' }}">
                    <p class="font-bold text-sm">1. Proses Muat</p>
                    <p class="text-[10px]">Foto Muatan</p>
                </div>
                <div class="flex-1 text-center py-2 rounded-xl {{ $activeTrip->status === 'in_transit' ? 'bg-brand-blue text-white shadow-md' : 'text-gray-500' }}">
                    <p class="font-bold text-sm">2. Proses Jalan</p>
                    <p class="text-[10px]">Cockpit & GPS</p>
                </div>
                <div class="flex-1 text-center py-2 rounded-xl {{ $activeTrip->status === 'unloading' ? 'bg-brand-blue text-white' : 'text-gray-500' }}">
                    <p class="font-bold text-sm">3. Bongkar</p>
                    <p class="text-[10px]">Foto Sampai</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Tab 1: Proses Muat -->
                @if($activeTab === 'loading')
                    <div class="border border-gray-200 p-6 rounded-2xl bg-white text-gray-800 shadow-sm">
                        <h3 class="font-bold mb-2 text-lg text-brand-blue">Instruksi Muat (Loading)</h3>
                        <p class="text-sm text-gray-500 mb-4">Silakan unggah foto barang saat dimuat ke truk untuk keperluan manifes Lion Parcel.</p>

                        <div class="mb-6">
                            <a href="{{ route('waybill', ['trip_id' => $activeTrip->id]) }}" target="_blank" class="w-full flex items-center justify-center gap-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 border border-yellow-200 font-bold py-2.5 px-4 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Lihat / Tanda Tangan Surat Jalan
                            </a>
                        </div>
                        
                        <form wire:submit.prevent="submitLoadingPhotos" class="flex flex-col space-y-4">
                            <x-camera-input modelName="photo_arrival" title="Bukti Kedatangan" requiredStatus="loading" :activeTrip="$activeTrip" :photoVar="$photo_arrival" />
                            <x-camera-input modelName="photo_loading" title="Bukti Proses Muat" requiredStatus="loading" :activeTrip="$activeTrip" :photoVar="$photo_loading" />
                            <x-camera-input modelName="photo_loaded" title="Bukti Selesai Muat" requiredStatus="loading" :activeTrip="$activeTrip" :photoVar="$photo_loaded" />
                            <x-camera-input modelName="document_loading" title="Dokumen Pendukung (Opsional)" requiredStatus="loading" :activeTrip="$activeTrip" :photoVar="$document_loading" />

                            <button type="submit" class="bg-brand-blue hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow w-full transition mt-4">Mulai Perjalanan</button>
                        </form>
                    </div>
                @endif

                <!-- Tab 2: Sedang Berjalan -->
                @if($activeTab === 'in_transit')
                    <div class="bg-brand-blue rounded-3xl p-6 text-white shadow-xl relative overflow-hidden border border-red-500">
                        <div class="absolute top-[-50px] right-[-50px] w-48 h-48 bg-blue-600 rounded-full opacity-50 blur-2xl"></div>
                        <div class="flex justify-between items-center mb-6 relative z-10">
                            <p class="text-xs font-bold tracking-wider text-blue-200">INSTRUKSI KORIDOR TOL:</p>
                            <span class="bg-white/20 px-3 py-1 rounded-full text-[10px] font-bold border border-blue-400">4.2 KM Menuju Rest Area</span>
                        </div>

                        <div class="flex items-start gap-4 mb-6 relative z-10">
                            <div class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow shrink-0">
                                <svg class="w-6 h-6 text-brand-blue transform -rotate-45" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-xl font-bold leading-tight">Tetap di Jalur Tol Trans Jawa</h3>
                                <p class="text-sm text-blue-100 mt-1">Titik Temu LTL Berikutnya: KM 207 Cirebon</p>
                            </div>
                        </div>

                        <div class="bg-black/20 rounded-xl p-3 flex items-center justify-between border border-red-500 mb-6 relative z-10">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full border-2 border-green-400 flex items-center justify-center"><div class="w-2 h-2 bg-green-400 rounded-full"></div></div>
                                <span class="text-sm text-white">Radius dari Jalur Tol:</span>
                            </div>
                            <div class="font-mono text-sm"><span class="font-bold">38m</span> <span class="text-blue-300">/ Max 100m</span></div>
                        </div>

                        <div class="grid grid-cols-3 gap-3 relative z-10">
                            <div class="bg-black/10 rounded-xl p-3 text-center border border-blue-400/50"><p class="text-[10px] text-blue-100 mb-1">Kecepatan</p><p class="font-bold text-lg">68 <span class="text-xs">km/h</span></p></div>
                            <div class="bg-black/10 rounded-xl p-3 text-center border border-blue-400/50"><p class="text-[10px] text-blue-100 mb-1">BBM Solar</p><p class="font-bold text-lg">64%</p></div>
                            <div class="bg-black/10 rounded-xl p-3 text-center border border-blue-400/50"><p class="text-[10px] text-blue-100 mb-1">Sisa Jarak</p><p class="font-bold text-lg">398 <span class="text-xs">KM</span></p></div>
                        </div>
                    </div>

                    <div class="mt-8">
                        <div class="flex justify-between items-end mb-4">
                            <h3 class="font-bold text-gray-800">Muatan di Dalam Bak Truk:</h3>
                            <div class="flex items-center gap-3">
                                <a href="{{ route('waybill', ['trip_id' => $activeTrip->id]) }}" target="_blank" class="bg-indigo-100 text-indigo-700 hover:bg-indigo-200 text-xs font-bold px-3 py-1.5 rounded-full flex items-center shadow-sm">
                                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                    Surat Jalan
                                </a>
                                <span class="text-brand-blue font-bold text-sm">Total 2.95 Ton</span>
                            </div>
                        </div>
                        <div class="space-y-3">
                            <div class="bg-white border border-gray-200 rounded-xl p-4 flex justify-between items-center shadow-sm">
                                <div><h4 class="text-gray-800 font-bold text-sm">1. Muatan Dedicated FTL (2.5 Ton)</h4><p class="text-gray-500 text-xs mt-1">Tujuan: Gudang SIER Rungkut Surabaya</p></div>
                                <span class="bg-blue-50 text-brand-blue text-[10px] px-3 py-1 rounded-full border border-blue-100">Terkunci</span>
                            </div>
                            <div class="bg-white border border-gray-200 rounded-xl p-4 flex justify-between items-center shadow-sm">
                                <div><h4 class="text-gray-800 font-bold text-sm">2. Flash Cargo LTL Sisa (450 kg)</h4><p class="text-gray-500 text-xs mt-1">Titik Temu: Rest Area KM 207 Cirebon</p></div>
                                <span class="bg-gray-100 text-gray-600 font-bold text-[10px] px-3 py-1 rounded-full border border-gray-200">Bak Belakang</span>
                            </div>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-8">
                        <button wire:click="toggleStopForm" class="bg-white text-brand-blue font-bold py-4 rounded-2xl flex flex-col items-center justify-center gap-1 shadow-sm hover:bg-blue-50 transition border border-gray-200 text-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                            <span class="text-xs">Check-in<br>Berhenti</span>
                        </button>
                        <button wire:click="toggleFlashSaleForm" class="bg-white text-green-600 font-bold py-4 rounded-2xl flex flex-col items-center justify-center gap-1 shadow-sm hover:bg-green-50 transition border border-green-200 text-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-xs">Buka Bid<br>(LTL Sisa)</span>
                        </button>
                        <button wire:click="toggleDeviationForm" class="bg-white text-orange-500 font-bold py-4 rounded-2xl flex flex-col items-center justify-center gap-1 shadow-sm hover:bg-orange-50 transition border border-gray-200 text-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-xs">Lapor<br>Deviasi</span>
                        </button>
                        <button wire:click="toggleUrgentForm" class="bg-red-50 text-red-600 font-bold py-4 rounded-2xl flex flex-col items-center justify-center gap-1 shadow-sm hover:bg-red-100 transition border border-red-200 text-center">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-xs">Darurat<br>(SOS)</span>
                        </button>
                    </div>

                    @if($showFlashSaleForm)
                        <div class="mt-4 bg-green-50 p-4 rounded-xl border border-green-200 shadow-sm">
                            <form wire:submit.prevent="openFlashSale" class="space-y-4">
                                <div class="text-green-700 bg-green-100 p-3 rounded text-sm mb-2">
                                    <strong>Flash Sale (LTL)</strong><br>
                                    Buka bid muatan parsial untuk sisa muatan Anda. Harga akan dihitung otomatis <strong>Rp1.000/kg</strong> secara sistem untuk membantu Merchant di rute Anda.
                                </div>
                                <div>
                                    <label class="text-xs text-green-800 font-bold">Lokasi Penjemputan LTL (Misal: Solo)</label>
                                    <input type="text" wire:model="flash_sale_pickup_location" class="w-full bg-white border border-green-300 rounded p-2 text-sm mt-1" placeholder="Masukkan kota penjemputan" required>
                                </div>
                                <button type="submit" class="w-full bg-green-600 text-white font-bold py-2 rounded shadow text-sm hover:bg-green-700">Buka Flash Sale LTL</button>
                            </form>
                        </div>
                    @endif

                    @if($showStopForm)
                        <div class="mt-4 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                            <form wire:submit.prevent="reportStop" class="space-y-4">
                                <x-camera-input modelName="photo" title="Foto Bukti Check-in (Opsional)" requiredStatus="in_transit" :activeTrip="$activeTrip" :photoVar="$photo" />
                                <div><label class="text-xs text-gray-500">Durasi (Menit)</label><input type="number" wire:model="stopDuration" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-sm mt-1" required></div>
                                <div><label class="text-xs text-gray-500">Alasan</label><textarea wire:model="stopReason" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-sm mt-1" rows="2" required></textarea></div>
                                <button type="submit" class="w-full bg-brand-blue text-white font-bold py-2 rounded shadow text-sm hover:bg-blue-700">Simpan Laporan</button>
                            </form>
                        </div>
                    @endif

                    @if($showDeviationForm)
                        <div class="mt-4 bg-orange-50 p-4 rounded-xl border border-orange-200">
                            <form wire:submit.prevent="reportDeviation" class="space-y-4">
                                <x-camera-input modelName="photo" title="Foto Deviasi (Opsional)" requiredStatus="in_transit" :activeTrip="$activeTrip" :photoVar="$photo" />
                                <div><label class="text-xs text-orange-800">Alasan Deviasi</label><textarea wire:model="deviationReason" class="w-full bg-white border border-orange-300 rounded p-2 text-sm mt-1" rows="2" required></textarea></div>
                                <button type="submit" class="w-full bg-orange-500 text-white font-bold py-2 rounded shadow text-sm hover:bg-orange-600">Simpan Laporan</button>
                            </form>
                        </div>
                    @endif

                    @if($showUrgentForm)
                        <div class="mt-4 bg-red-50 p-4 rounded-xl border border-red-200">
                            <div class="mb-4 text-red-700 bg-red-100 p-3 rounded text-sm">
                                <strong>Peringatan!</strong> Gunakan tombol ini HANYA jika Anda tidak bisa melanjutkan perjalanan (ban pecah, mogok parah, atau over-kapasitas). Sistem akan membatalkan trip Anda dan mencarikan driver pengganti otomatis.
                            </div>
                            <form wire:submit.prevent="reportUrgentIssue" class="space-y-4">
                                <x-camera-input modelName="photo" title="Foto Bukti Kendala (Wajib)" requiredStatus="in_transit" :activeTrip="$activeTrip" :photoVar="$photo" />
                                <div>
                                    <label class="text-xs text-red-800 font-bold">Jenis Kendala</label>
                                    <select wire:model="urgentType" class="w-full bg-white border border-red-300 rounded p-2 text-sm mt-1" required>
                                        <option value="">Pilih Kendala...</option>
                                        <option value="vehicle_breakdown">Truk Mogok / Ban Pecah / Laka</option>
                                        <option value="capacity_mismatch">Kapasitas / Tonase Tidak Sesuai (Fraud Merchant)</option>
                                        <option value="driver_sick">Driver Sakit / Force Majeure</option>
                                        <option value="other">Lainnya</option>
                                    </select>
                                </div>
                                <div><label class="text-xs text-red-800 font-bold">Detail Alasan & Kondisi</label><textarea wire:model="urgentReason" class="w-full bg-white border border-red-300 rounded p-2 text-sm mt-1" rows="3" placeholder="Ceritakan detail kendala. Jika indikasi fraud, jelaskan aktual di lapangan." required></textarea></div>
                                <button type="submit" class="w-full bg-red-600 text-white font-bold py-3 rounded-xl shadow text-sm hover:bg-red-700 mt-2">Laporkan & Batalkan Trip (SOS)</button>
                            </form>
                        </div>
                    @endif

                    <div class="mt-8 border-t border-gray-200 pt-6 flex flex-col items-center">
                        <button wire:click="setTab('unloading')" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-8 rounded-xl shadow transition">Lanjut ke Proses Bongkar</button>
                    </div>
                @endif
                
                <!-- Tab 3: Bongkar -->
                @if($activeTab === 'unloading')
                    <div class="border border-gray-200 p-6 rounded-2xl bg-white text-gray-800 shadow-sm">
                        <h3 class="font-bold mb-2 text-lg text-brand-blue">Form Penyelesaian (Bongkar)</h3>
                        <p class="text-sm text-gray-500 mb-4">Silakan unggah foto barang saat dibongkar sebelum menyelesaikan perjalanan.</p>

                        <div class="mb-6">
                            <a href="{{ route('waybill', ['trip_id' => $activeTrip->id]) }}" target="_blank" class="w-full flex items-center justify-center gap-2 bg-yellow-100 hover:bg-yellow-200 text-yellow-800 border border-yellow-200 font-bold py-2.5 px-4 rounded-xl transition">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                Lihat / Tanda Tangan Surat Jalan
                            </a>
                        </div>
                        
                        <form wire:submit.prevent="submitUnloadingPhotos" class="flex flex-col space-y-4">
                            <x-camera-input modelName="photo_destination" title="Bukti Sampai di Tujuan" requiredStatus="unloading" :activeTrip="$activeTrip" :photoVar="$photo_destination" />
                            <x-camera-input modelName="photo_unloading" title="Bukti Proses Bongkar" requiredStatus="unloading" :activeTrip="$activeTrip" :photoVar="$photo_unloading" />
                            <x-camera-input modelName="document_unloading" title="Dokumen Pendukung (Opsional)" requiredStatus="unloading" :activeTrip="$activeTrip" :photoVar="$document_unloading" />
                            
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl shadow w-full transition mt-4">Selesaikan Perjalanan</button>
                        </form>
                    </div>
                @endif
            </div>
            
            <div class="mt-12 flex justify-between items-center text-xs text-gray-500">
                <span>Butuh Bantuan Kendala Truk?</span>
                <a href="#" class="text-brand-blue font-bold flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> Hubungi Dispatcher 24/7</a>
            </div>
        @else
            <div class="text-center text-gray-500 p-12 bg-white border border-gray-300 rounded-2xl border-dashed mt-8">
                <p class="font-bold text-lg text-gray-700">Tidak Ada Trip Aktif</p>
                <a href="{{ route('bidding') }}" class="mt-6 inline-block bg-brand-blue text-white font-bold py-2 px-6 rounded-xl">Cari Muatan</a>
            </div>
        @endif
    </div>

    @if($activeTrip && $activeTrip->status === 'in_transit')
        <script>
            document.addEventListener('livewire:initialized', () => {
                if (navigator.geolocation) {
                    navigator.geolocation.watchPosition((position) => {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        @this.call('updateLocation', lat, lng);
                    }, () => {}, { enableHighAccuracy: true, maximumAge: 5000, timeout: 5000 });
                }
            });
        </script>
    @endif

    <!-- Photo Preview Modal -->
    <div x-data="{ isPreviewOpen: false, previewUrl: '' }" @preview-photo.window="previewUrl = $event.detail; isPreviewOpen = true">
        <div x-show="isPreviewOpen" style="display: none;" class="fixed inset-0 z-[9999] bg-black/95 flex flex-col items-center justify-center p-4">
            <button @click="isPreviewOpen = false" type="button" class="absolute top-6 right-6 bg-white/20 text-white p-3 rounded-full hover:bg-white/40 transition">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
            <img :src="previewUrl" class="max-w-full max-h-[85vh] rounded-xl object-contain shadow-2xl border border-gray-800">
            <p class="text-gray-400 mt-4 font-bold text-sm">Preview Hasil Foto & Watermark</p>
        </div>
    </div>

    <!-- WebRTC Camera Modal -->
    <div x-data="cameraModal()" @open-camera.window="openCamera($event.detail)" style="display: none;" x-show="isOpen">
        <div class="fixed inset-0 z-[9999] bg-black flex flex-col">
            <div class="flex-1 relative w-full h-full bg-black flex items-center justify-center">
                <video x-ref="video" autoplay playsinline class="w-full h-full object-contain"></video>
                <button @click="closeCamera" type="button" class="absolute top-6 right-6 bg-black/50 text-white p-3 rounded-full hover:bg-black/80 transition"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg></button>
                
                <div class="absolute bottom-10 left-0 right-0 flex justify-center items-center gap-8">
                    <button @click="switchCamera" type="button" class="bg-gray-800/80 text-white p-4 rounded-full shadow-lg"><svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg></button>
                    <button @click="takePhoto" type="button" class="bg-white rounded-full w-20 h-20 border-4 border-gray-400 shadow-xl flex items-center justify-center hover:scale-105 transition"><div class="w-16 h-16 rounded-full border-2 border-black"></div></button>
                    <div class="w-14"></div>
                </div>
            </div>
            <canvas x-ref="canvas" class="hidden"></canvas>
        </div>
    </div>

    <script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('cameraModal', () => ({
            isOpen: false, stream: null, facingMode: 'environment', targetModel: null,

            async openCamera(modelName) {
                this.targetModel = modelName;
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: this.facingMode } } });
                    this.isOpen = true;
                    this.$refs.video.srcObject = this.stream;
                } catch (err) {
                    const input = document.getElementById('input_' + modelName);
                    if (input) input.click();
                }
            },
            async startStream() {
                if (this.stream) this.stream.getTracks().forEach(track => track.stop());
                try {
                    this.stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: { ideal: this.facingMode } } });
                    this.$refs.video.srcObject = this.stream;
                } catch (err) {}
            },
            switchCamera() { this.facingMode = this.facingMode === 'environment' ? 'user' : 'environment'; this.startStream(); },
            takePhoto() {
                const video = this.$refs.video; const canvas = this.$refs.canvas;
                canvas.width = video.videoWidth; canvas.height = video.videoHeight;
                const context = canvas.getContext('2d');
                if (this.facingMode === 'user') { context.translate(canvas.width, 0); context.scale(-1, 1); }
                context.drawImage(video, 0, 0, canvas.width, canvas.height);
                this.applyWatermark(context, canvas);
                canvas.toBlob((blob) => {
                    const file = new File([blob], "camera_capture.jpg", { type: "image/jpeg" });
                    @this.upload(this.targetModel, file, () => {}, () => { alert('Gagal mengunggah foto.'); });
                    this.closeCamera();
                }, 'image/jpeg', 0.85);
            },
            handleFallback(event, modelName) {
                const file = event.target.files[0];
                if (!file) return;
                const img = new Image();
                img.onload = () => {
                    const canvas = this.$refs.canvas;
                    const maxWidth = 1200; let width = img.width; let height = img.height;
                    if (width > maxWidth) { height = Math.floor(height * (maxWidth / width)); width = maxWidth; }
                    canvas.width = width; canvas.height = height;
                    const context = canvas.getContext('2d');
                    context.drawImage(img, 0, 0, width, height);
                    this.applyWatermark(context, canvas);
                    canvas.toBlob((blob) => {
                        const newFile = new File([blob], "upload_capture.jpg", { type: "image/jpeg" });
                        @this.upload(modelName, newFile, () => {}, () => { alert('Gagal mengunggah foto.'); });
                    }, 'image/jpeg', 0.85);
                };
                img.src = URL.createObjectURL(file);
            },
            applyWatermark(context, canvas) {
                context.setTransform(1, 0, 0, 1, 0, 0);
                const lat = "{{ $activeTrip ? ($activeTrip->current_lat ?? 'Menunggu GPS') : 'Menunggu GPS' }}";
                const lng = "{{ $activeTrip ? ($activeTrip->current_lng ?? 'Menunggu GPS') : 'Menunggu GPS' }}";
                const now = new Date(); const pad = (n) => n.toString().padStart(2, '0');
                const timeStr = `${now.getFullYear()}-${pad(now.getMonth()+1)}-${pad(now.getDate())} ${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
                const text1 = "Waktu : " + timeStr; const text2 = "Lokasi: " + lat + ", " + lng; const text3 = "Driver: {{ Auth::user()->name ?? 'Driver' }}";
                const fontSize = Math.max(14, Math.floor(canvas.width * 0.035));
                context.font = `bold ${fontSize}px sans-serif`;
                const padding = fontSize; const boxHeight = (fontSize * 3.5) + (padding * 2);
                const boxWidth = Math.max(context.measureText(text2).width, context.measureText(text3).width, context.measureText(text1).width) + padding * 2;
                context.fillStyle = "rgba(0, 0, 0, 0.5)"; context.fillRect(10, canvas.height - boxHeight - 10, boxWidth, boxHeight);
                context.fillStyle = "white";
                context.fillText(text1, 10 + padding, canvas.height - boxHeight - 10 + padding + fontSize * 1);
                context.fillText(text2, 10 + padding, canvas.height - boxHeight - 10 + padding + fontSize * 2.2);
                context.fillText(text3, 10 + padding, canvas.height - boxHeight - 10 + padding + fontSize * 3.4);
            },
            closeCamera() { this.isOpen = false; if (this.stream) { this.stream.getTracks().forEach(track => track.stop()); this.stream = null; } }
        }));
    });
    </script>
</div>
