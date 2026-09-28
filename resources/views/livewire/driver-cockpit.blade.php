<div class="min-h-screen bg-gray-50 -mt-8 py-8 px-4 sm:px-6 lg:px-8 font-sans">
    <div class="max-w-2xl mx-auto">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 bg-white p-4 rounded-2xl border border-gray-200 shadow-sm">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-brand-blue rounded-full flex items-center justify-center font-bold text-white text-xl">
                    LP
                </div>
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
                <div class="flex-1 text-center py-2 rounded-xl {{ $activeTrip->status === 'completed' ? 'bg-brand-blue text-white' : 'text-gray-500' }}">
                    <p class="font-bold text-sm">3. Bongkar</p>
                    <p class="text-[10px]">Foto Sampai</p>
                </div>
            </div>

            <div class="space-y-6">
                <!-- Proses Muat -->
                @if($activeTrip->status === 'loading')
                    <div class="border border-gray-200 p-6 rounded-2xl bg-white text-gray-800 shadow-sm">
                        <h3 class="font-bold mb-2 text-lg text-brand-blue">Instruksi Muat (Loading)</h3>
                        <p class="text-sm text-gray-500 mb-6">Silakan unggah foto barang saat dimuat ke truk untuk keperluan manifes Lion Parcel.</p>
                        
                        <form wire:submit.prevent="uploadPhoto('loading')" class="flex flex-col space-y-4">
                            <!-- capture="environment" to allow camera specifically -->
                            <input type="file" accept="image/*" capture="environment" wire:model="photo" class="text-sm bg-gray-50 rounded-lg p-2 border border-gray-300">
                            <button type="submit" class="bg-brand-blue hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-xl shadow w-full transition">Upload & Simpan</button>
                        </form>
                        @error('photo') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror

                        @if($activeTrip->photos->where('type', 'loading')->count() > 0)
                            <div class="mt-6 pt-6 border-t border-gray-100 flex flex-col space-y-4">
                                @if(!$activeTrip->flash_sale_expires_at)
                                    <button wire:click="openFlashSale" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-3 px-6 rounded-xl shadow w-full transition flex justify-center items-center">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                        Buka Sisa Muatan (Flash Sale)
                                    </button>
                                @endif
                                <button wire:click="startDriving" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-6 rounded-xl shadow w-full transition flex justify-center items-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Mulai Perjalanan (Start Trip)
                                </button>
                            </div>
                        @endif
                    </div>
                @endif

                <!-- Sedang Berjalan -->
                @if($activeTrip->status === 'in_transit')
                    <!-- Top Red Box (Keep red for emphasis but adjust inner colors for contrast) -->
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

                        <!-- Deviation Tracker -->
                        <div class="bg-black/20 rounded-xl p-3 flex items-center justify-between border border-red-500 mb-6 relative z-10">
                            <div class="flex items-center gap-2">
                                <div class="w-5 h-5 rounded-full border-2 border-green-400 flex items-center justify-center">
                                    <div class="w-2 h-2 bg-green-400 rounded-full"></div>
                                </div>
                                <span class="text-sm text-white">Radius dari Jalur Tol:</span>
                            </div>
                            <div class="font-mono text-sm">
                                <span class="font-bold">38m</span> <span class="text-blue-300">/ Max 100m</span>
                            </div>
                        </div>

                        <!-- Vitals -->
                        <div class="grid grid-cols-3 gap-3 relative z-10">
                            <div class="bg-black/10 rounded-xl p-3 text-center border border-blue-400/50">
                                <p class="text-[10px] text-blue-100 mb-1">Kecepatan</p>
                                <p class="font-bold text-lg">68 <span class="text-xs">km/h</span></p>
                            </div>
                            <div class="bg-black/10 rounded-xl p-3 text-center border border-blue-400/50">
                                <p class="text-[10px] text-blue-100 mb-1">BBM Solar</p>
                                <p class="font-bold text-lg">64%</p>
                            </div>
                            <div class="bg-black/10 rounded-xl p-3 text-center border border-blue-400/50">
                                <p class="text-[10px] text-blue-100 mb-1">Sisa Jarak</p>
                                <p class="font-bold text-lg">398 <span class="text-xs">KM</span></p>
                            </div>
                        </div>
                    </div>

                    <!-- Cargo List -->
                    <div class="mt-8">
                        <div class="flex justify-between items-end mb-4">
                            <h3 class="font-bold text-gray-800">Muatan di Dalam Bak Truk:</h3>
                            <span class="text-brand-blue font-bold text-sm">Total 2.95 Ton</span>
                        </div>

                        <div class="space-y-3">
                            <div class="bg-white border border-gray-200 rounded-xl p-4 flex justify-between items-center shadow-sm">
                                <div>
                                    <h4 class="text-gray-800 font-bold text-sm">1. Muatan Dedicated FTL (2.5 Ton)</h4>
                                    <p class="text-gray-500 text-xs mt-1">Tujuan: Gudang SIER Rungkut Surabaya</p>
                                </div>
                                <span class="bg-blue-50 text-brand-blue text-[10px] px-3 py-1 rounded-full border border-blue-100">Terkunci</span>
                            </div>
                            
                            <div class="bg-white border border-gray-200 rounded-xl p-4 flex justify-between items-center shadow-sm">
                                <div>
                                    <h4 class="text-gray-800 font-bold text-sm">2. Flash Cargo LTL Sisa (450 kg)</h4>
                                    <p class="text-gray-500 text-xs mt-1">Titik Temu: Rest Area KM 207 Cirebon</p>
                                </div>
                                <span class="bg-gray-100 text-gray-600 font-bold text-[10px] px-3 py-1 rounded-full border border-gray-200">Bak Belakang</span>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="grid grid-cols-2 gap-4 mt-8">
                        <button wire:click="toggleStopForm" class="bg-white text-brand-blue font-bold py-4 rounded-2xl flex flex-col items-center justify-center gap-1 shadow-sm hover:bg-blue-50 transition border border-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            <span class="text-sm">Check-in Berhenti</span>
                        </button>
                        <button wire:click="toggleDeviationForm" class="bg-white text-orange-500 font-bold py-4 rounded-2xl flex flex-col items-center justify-center gap-1 shadow-sm hover:bg-orange-50 transition border border-gray-200">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                            <span class="text-sm">Lapor Deviasi Jalur</span>
                        </button>
                    </div>

                    <!-- Stop Form -->
                    @if($showStopForm)
                        <div class="mt-4 bg-white p-4 rounded-xl border border-gray-200 shadow-sm">
                            <h4 class="font-bold mb-3 text-sm text-brand-blue">Lapor Pemberhentian Manual</h4>
                            <form wire:submit.prevent="reportStop" class="space-y-3">
                                <div>
                                    <label class="text-xs text-gray-500">Durasi (Menit)</label>
                                    <input type="number" wire:model="stopDuration" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-sm mt-1" required>
                                </div>
                                <div>
                                    <label class="text-xs text-gray-500">Alasan / Catatan</label>
                                    <textarea wire:model="stopReason" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-sm mt-1" rows="2" required></textarea>
                                </div>
                                <!-- Extra Feature: Upload Photo for Check-in -->
                                <div>
                                    <label class="text-xs text-gray-500">Foto Bukti Check-in (Opsional)</label>
                                    <input type="file" accept="image/*" capture="environment" wire:model="photo" class="w-full bg-gray-50 border border-gray-300 rounded p-2 text-sm mt-1">
                                </div>
                                <button type="submit" class="w-full bg-brand-blue text-white font-bold py-2 rounded shadow text-sm hover:bg-blue-700">Simpan Laporan</button>
                            </form>
                        </div>
                    @endif

                    <!-- Deviation Form -->
                    @if($showDeviationForm)
                        <div class="mt-4 bg-orange-50 p-4 rounded-xl border border-orange-200">
                            <h4 class="font-bold mb-3 text-sm text-orange-600">Lapor Keluar Jalur (Deviasi)</h4>
                            <form wire:submit.prevent="reportDeviation" class="space-y-3">
                                <div>
                                    <label class="text-xs text-orange-800">Alasan Deviasi</label>
                                    <textarea wire:model="deviationReason" class="w-full bg-white border border-orange-300 rounded p-2 text-sm mt-1" rows="2" required placeholder="Contoh: Macet total, dialihkan petugas..."></textarea>
                                </div>
                                <button type="submit" class="w-full bg-orange-500 text-white font-bold py-2 rounded shadow text-sm hover:bg-orange-600">Simpan Laporan</button>
                            </form>
                        </div>
                    @endif
                    
                    <div class="mt-8 border-t border-gray-200 pt-6 bg-white p-6 rounded-2xl shadow-sm">
                        <h3 class="font-bold text-gray-800 mb-2 text-sm text-center">Form Penyelesaian (Bongkar)</h3>
                        <p class="text-xs text-gray-500 text-center mb-4">Unggah foto barang saat dibongkar sebelum menyelesaikan perjalanan.</p>
                        <form wire:submit.prevent="uploadPhoto('unloading')" class="flex flex-col space-y-4">
                            <!-- capture="environment" to allow camera specifically -->
                            <input type="file" accept="image/*" capture="environment" wire:model="photo" class="text-sm bg-gray-50 rounded-lg p-2 border border-gray-300 text-gray-800">
                            <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-xl shadow w-full transition">Selesaikan Perjalanan</button>
                        </form>
                        @error('photo') <span class="text-red-500 text-xs block mt-2">{{ $message }}</span> @enderror
                    </div>
                @endif
                
                @if($activeTrip->status === 'completed')
                    <div class="text-center p-8 bg-white shadow-sm border border-gray-200 rounded-2xl">
                        <div class="w-16 h-16 bg-green-100 text-green-600 rounded-full flex items-center justify-center mx-auto mb-4">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <h3 class="font-bold text-2xl text-gray-800 mb-2">Trip Selesai!</h3>
                        <p class="text-gray-500">Terima kasih. Anda telah menyelesaikan pengiriman ini dengan baik. Silakan cek menu Bidding untuk mencari muatan balikan.</p>
                        <a href="{{ route('feed') }}" class="mt-6 inline-block bg-brand-blue hover:bg-blue-700 text-white font-bold py-3 px-8 rounded-xl shadow transition">Kembali ke Feed</a>
                    </div>
                @endif
            </div>
            
            <div class="mt-12 flex justify-between items-center text-xs text-gray-500">
                <span>Butuh Bantuan Kendala Truk?</span>
                <a href="#" class="text-brand-blue font-bold flex items-center"><svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg> Hubungi Dispatcher 24/7</a>
            </div>
        @else
            <div class="text-center text-gray-500 p-12 bg-white border border-gray-300 rounded-2xl border-dashed mt-8">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                <p class="font-bold text-lg text-gray-700">Tidak Ada Trip Aktif</p>
                <p class="mt-2 text-sm">Silakan cari dan menangkan bidding muatan di Flash Market.</p>
                <a href="{{ route('driver.bidding') }}" class="mt-6 inline-block bg-brand-blue hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-xl shadow transition">Cari Muatan LTL</a>
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
                        
                        console.log("GPS Lock:", lat, lng);
                        @this.call('updateLocation', lat, lng);
                    }, (error) => {
                        console.error("GPS Error:", error);
                    }, {
                        enableHighAccuracy: true,
                        maximumAge: 5000,
                        timeout: 5000
                    });
                }
            });
        </script>
    @endif
</div>
