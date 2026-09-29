<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
            {{ session('message') }}
        </div>
    @endif
    
    @if (session()->has('error'))
        <div x-data="{ show: true }" x-show="show" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900 bg-opacity-75 transition-opacity" style="display: none;">
            <div @click.away="show = false" class="bg-white rounded-3xl shadow-2xl p-8 w-full max-w-md transform transition-all relative overflow-hidden">
                <!-- Red top border -->
                <div class="absolute top-0 left-0 right-0 h-2 bg-red-600"></div>
                
                <div class="text-center">
                    <div class="mx-auto flex items-center justify-center h-20 w-20 rounded-full bg-red-50 mb-6">
                        <svg class="h-10 w-10 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                        </svg>
                    </div>
                    <h3 class="text-2xl font-bold text-gray-900 mb-2">Akses Terbatas</h3>
                    <p class="text-sm text-gray-600 mb-8">{{ session('error') }}</p>
                    
                    @if(!Auth::user()->hasRole('merchant') && !Auth::user()->hasRole('driver'))
                        <div class="flex flex-col gap-3">
                            <a href="{{ route('profile.edit') }}" class="w-full flex justify-center items-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-brand-blue hover:bg-blue-700 focus:outline-none transition-transform transform hover:-translate-y-0.5">
                                Ayo Pilih Role-mu Sekarang
                                <svg class="w-4 h-4 ml-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                            </a>
                            <button @click="show = false" type="button" class="w-full flex justify-center py-3.5 px-4 border border-gray-200 rounded-xl shadow-sm text-sm font-bold text-gray-600 bg-white hover:bg-gray-50 focus:outline-none transition">
                                Nanti Saja, Saya Hanya Ingin Melihat
                            </button>
                        </div>
                    @else
                        <button @click="show = false" type="button" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-xl shadow-md text-sm font-bold text-white bg-red-600 hover:bg-red-700 focus:outline-none transition">
                            Tutup
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

    @if($isMerchant)
        <!-- Ad Banner for Merchant -->
        <div class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl shadow-lg p-6 mb-8 flex flex-col md:flex-row items-center justify-between border border-gray-700">
            <div class="text-white mb-4 md:mb-0">
                <h3 class="text-xl font-bold mb-1 flex items-center">
                    <svg class="w-6 h-6 text-yellow-400 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Tingkatkan Exposure Muatan Anda!
                </h3>
                <p class="text-gray-300 text-sm">Pasang iklan di Flash Market untuk tampil di posisi paling atas dan dapatkan driver lebih cepat.</p>
            </div>
            <a href="{{ route('merchant.ad-apply') }}" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 font-bold py-2 px-6 rounded-xl transition shadow-sm whitespace-nowrap">
                Pelajari Lebih Lanjut
            </a>
        </div>

        <div class="bg-white p-6 rounded-2xl shadow-sm mb-8 border border-gray-100">
            <h2 class="text-2xl font-bold mb-6 text-brand-black">Buat Order Muatan</h2>
            
            @if($repost_suggested_price)
                <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700">
                                Sebelumnya harga yang Anda ajukan tidak menarik bagi driver. Rata-rata saran harga dari driver adalah <strong>Rp {{ number_format($repost_suggested_price, 0, ',', '.') }}</strong>.
                            </p>
                            <p class="mt-2">
                                <button type="button" wire:click="applySuggestedPrice" class="text-sm font-medium text-yellow-700 underline hover:text-yellow-600">Gunakan Harga Saran</button>
                                <span class="mx-2 text-yellow-700">|</span>
                                <button type="button" wire:click="$set('repost_suggested_price', null)" class="text-sm font-medium text-yellow-700 underline hover:text-yellow-600">Abaikan</button>
                            </p>
                        </div>
                    </div>
                </div>
            @endif

            <form wire:submit.prevent="createLoad" class="space-y-8">
                
                <!-- Box 1: Info Barang -->
                <div class="bg-gray-50 p-6 rounded-xl border border-gray-200">
                    <h3 class="text-lg font-bold mb-4 text-brand-blue flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg> 1. Info Barang</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700">Judul Trip</label>
                            <input type="text" wire:model="title" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm" placeholder="Cth: Pengiriman Sparepart Cikarang - Karawang">
                            @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Nama Barang</label>
                            <input type="text" wire:model="item_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm" placeholder="Cth: Sparepart Motor">
                            @error('item_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tipe Layanan</label>
                            <select wire:model.live="type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-blue focus:border-brand-blue">
                                <option value="FTL">FTL (Full Truckload)</option>
                                <option value="LTL">LTL (Sharing / Flash Sale)</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Total Berat (KG)</label>
                            <input type="number" wire:model="weight_kg" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm" placeholder="Cth: 1500">
                            @error('weight_kg') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Jumlah Koli (Opsional)</label>
                            <input type="number" wire:model="koli" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm" placeholder="Cth: 50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Kebutuhan Kendaraan</label>
                            <select wire:model="vehicle_type_needed" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-brand-blue focus:border-brand-blue">
                                <option value="">-- Pilih Kendaraan --</option>
                                <option value="Engkel Bak">Engkel Bak</option>
                                <option value="Engkel Box">Engkel Box</option>
                                <option value="CDD Bak">CDD Bak</option>
                                <option value="CDD Box">CDD Box</option>
                                <option value="Fuso">Fuso</option>
                                <option value="Blindvan">Blindvan</option>
                                <option value="Pickup">Pickup</option>
                            </select>
                            @error('vehicle_type_needed') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div x-data="{ 
                                rawPrice: $wire.entangle('max_price'),
                                formattedPrice: '',
                                init() {
                                    this.formattedPrice = this.rawPrice ? this.format(this.rawPrice) : '';
                                    $watch('rawPrice', value => {
                                        if(value !== this.parse(this.formattedPrice)) {
                                            this.formattedPrice = value ? this.format(value) : '';
                                        }
                                    });
                                },
                                format(value) {
                                    return new Intl.NumberFormat('id-ID').format(value);
                                },
                                parse(value) {
                                    return parseInt(value.toString().replace(/[^0-9]/g, '')) || 0;
                                },
                                updatePrice(e) {
                                    let val = this.parse(e.target.value);
                                    this.formattedPrice = this.format(val);
                                    this.rawPrice = val;
                                }
                            }">
                            <label class="block text-sm font-medium text-gray-700">Harga Maksimal Ongkir (Rp)</label>
                            <div class="relative mt-1 rounded-md shadow-sm">
                                <div class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3">
                                    <span class="text-gray-500 sm:text-sm">Rp</span>
                                </div>
                                <input type="text" x-model="formattedPrice" @input="updatePrice" class="block w-full rounded-md border-gray-300 pl-9 focus:border-brand-blue focus:ring-brand-blue sm:text-sm">
                            </div>
                            @error('max_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <!-- Box 2: Info Pengirim -->
                    <div class="bg-blue-50 p-6 rounded-xl border border-blue-100">
                        <h3 class="text-lg font-bold mb-4 text-blue-800 flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> 2. Info Pengirim</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Pengirim</label>
                                <input type="text" wire:model="sender_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('sender_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <input type="text" wire:model="sender_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm">
                                @error('sender_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat Penjemputan</label>
                                <textarea wire:model="sender_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm"></textarea>
                                @error('sender_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>

                    <!-- Box 3: Info Penerima -->
                    <div class="bg-green-50 p-6 rounded-xl border border-green-100">
                        <h3 class="text-lg font-bold mb-4 text-green-800 flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg> 3. Info Penerima</h3>
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Nama Penerima</label>
                                <input type="text" wire:model="receiver_name" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                @error('receiver_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">No. Telepon</label>
                                <input type="text" wire:model="receiver_phone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                                @error('receiver_phone') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Alamat Tujuan & Pinpoint</label>
                                <textarea wire:model="receiver_address" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="Tulis alamat lengkap..."></textarea>
                                @error('receiver_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Perkiraan Jarak (KM)</label>
                                <input type="number" wire:model="distance" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Settings -->
                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    <div class="flex items-center">
                        <input id="paylater" type="checkbox" wire:model="is_paylater" class="h-5 w-5 text-brand-blue focus:ring-brand-blue border-gray-300 rounded">
                        <label for="paylater" class="ml-3 block text-sm font-medium text-gray-800">
                            Gunakan Hak Eksklusif <span class="font-bold">(Cargo Fee)</span>
                        </label>
                    </div>
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Batas Bidding (Opsional):</label>
                        <input type="datetime-local" wire:model="bid_deadline" class="rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm">
                    </div>
                    @if(!in_array(strtolower(Auth::user()->tier ?? 'common'), ['common', 'not_eligible']))
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Min. Tier Driver (Opsional):</label>
                        <select wire:model="min_driver_tier" class="rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm">
                            <option value="">Semua Tier</option>
                            <option value="bronze">Bronze ke atas</option>
                            <option value="silver">Silver ke atas</option>
                            <option value="gold">Hanya Gold</option>
                        </select>
                    </div>
                    @endif
                </div>

                <div class="text-right">
                    <button type="submit" class="bg-brand-blue hover:bg-blue-700 text-white py-3 px-8 rounded-full shadow-lg font-bold text-lg transition-transform transform hover:scale-105">
                        Posting Order ke Bursa
                    </button>
                </div>
            </form>
        </div>

        @if(isset($recommendedDrivers) && $recommendedDrivers->count() > 0)
            <div class="mb-8 bg-gradient-to-r from-gray-50 to-white rounded-3xl p-6 border border-gray-200 shadow-sm">
                <h3 class="text-lg font-bold text-gray-900 mb-4 flex items-center">
                    <svg class="w-6 h-6 text-brand-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg>
                    Rekomendasi Driver Dedicated Terbaik (Area Anda)
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @foreach($recommendedDrivers as $driver)
                        <div class="bg-white border border-gray-100 rounded-xl p-4 flex items-center space-x-4 hover:shadow-md transition cursor-pointer">
                            <div class="flex-shrink-0">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=random" class="w-12 h-12 rounded-full border border-gray-200">
                            </div>
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-1">
                                    <p class="text-sm font-bold text-gray-900 truncate">{{ $driver->name }}</p>
                                    @if($driver->tier)
                                        <x-tier-badge :tier="$driver->tier" class="scale-[0.8] origin-left" />
                                    @endif
                                </div>
                                <div class="flex items-center mt-1">
                                    <svg class="w-4 h-4 text-yellow-400" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                    <span class="text-xs text-gray-600 font-medium ml-1">{{ number_format($driver->ratings_as_ratee_avg_score ?? 0, 1) }} / 5.0</span>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    @else
        <div class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-brand-black uppercase tracking-wide">Flash Market (Bursa Muatan)</h3>
            <div class="w-64 hidden md:block">
                <input type="text" wire:model.live="search" placeholder="Cari merchant atau tipe..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm">
            </div>
        </div>

        <!-- FIXED TOP BOXES (Top Bid & Iklan) -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- 3 Top Bids -->
            @for ($i = 0; $i < 3; $i++)
                @php
                    $merchant = $topBidMerchants[$i] ?? null;
                    // Dummy Cargo Data
                    $dummyTujuan = ['Jakarta - Surabaya', 'Bandung - Semarang', 'Cikarang - Karawang'];
                    $dummyBarang = ['Sparepart Motor', 'Elektronik', 'Kain Tekstil'];
                    $dummyHarga = [1500000, 850000, 450000];
                    $dummyBerat = [2500, 1000, 800];
                @endphp
                <div class="bg-white rounded-3xl shadow-sm overflow-hidden border-2 border-yellow-400 flex flex-col relative transform transition hover:scale-105">
                    <div class="absolute top-0 right-0 bg-yellow-400 text-xs font-bold px-3 py-1 rounded-bl-lg">TOP BID #{{ $i+1 }}</div>
                    <div class="bg-gray-900 text-white p-5">
                        <h4 class="text-lg font-bold mt-2 truncate">{{ $merchant ? $merchant->name : 'Merchant Premium' }}</h4>
                        <p class="text-xs text-gray-400 truncate">{{ $merchant ? $dummyBarang[$i] : 'Muatan Menarik' }}</p>
                    </div>
                    <div class="p-5 flex-grow flex flex-col justify-between">
                        <div>
                            <p class="text-xs font-bold text-gray-500 mb-1">Rute (Contoh):</p>
                            <p class="text-sm font-semibold mb-3">{{ $merchant ? $dummyTujuan[$i] : 'Rute Antar Kota' }}</p>
                            
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-xs text-gray-500">Kapasitas:</span>
                                <span class="text-sm font-bold">{{ $merchant ? $dummyBerat[$i] : 1000 }} KG</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-xs text-gray-500">Tarif:</span>
                                <span class="text-brand-blue font-bold">Rp {{ number_format($merchant ? $dummyHarga[$i] : 1000000, 0, ',', '.') }}</span>
                            </div>
                        </div>
                        <div class="mt-4 pt-3 border-t border-gray-100 text-center">
                            <span class="text-[10px] text-gray-400 uppercase tracking-wider">Performa Tinggi</span>
                        </div>
                    </div>
                </div>
            @endfor

            <!-- 1 Iklan -->
            <div class="bg-gradient-to-br from-blue-900 to-blue-700 rounded-3xl shadow-lg overflow-hidden flex flex-col relative transform transition hover:scale-105 text-white">
                <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg">IKLAN SPONSOR</div>
                
                @if($activeAd)
                    <div class="p-5 flex-grow flex flex-col justify-center text-center">
                        <div class="w-16 h-16 bg-white rounded-full mx-auto mb-4 flex items-center justify-center text-blue-900 font-bold text-2xl shadow-inner">
                            {{ substr($activeAd->name, 0, 1) }}
                        </div>
                        <h4 class="text-lg font-bold mb-1">{{ $activeAd->name }}</h4>
                        <p class="text-xs text-blue-200 mb-4">Mitra Terpercaya Kargokita</p>
                        
                        <div class="bg-white/20 rounded-xl p-3 backdrop-blur-sm">
                            <p class="text-sm font-semibold">Tersedia banyak muatan setiap hari!</p>
                            <p class="text-xs mt-1">Gunakan fitur pencarian untuk menemukan muatan dari kami.</p>
                        </div>
                    </div>
                @else
                    <div class="p-5 flex-grow flex flex-col justify-center text-center border-2 border-dashed border-blue-400/50 m-2 rounded-2xl">
                        <svg class="w-10 h-10 mx-auto text-blue-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <h4 class="text-lg font-bold mb-1">Space Iklan Tersedia</h4>
                        <p class="text-xs text-blue-200">Hubungi Administrator untuk memasang iklan perusahaan Anda di sini.</p>
                    </div>
                @endif
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($loads as $load)
                <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100 flex flex-col">
                    <!-- Header -->
                    <div class="bg-brand-blue text-white p-6 relative">
                        <span class="bg-white text-brand-blue text-xs font-bold px-2 py-1 rounded absolute top-4 left-4">FLS-{{ str_pad($load->id, 3, '0', STR_PAD_LEFT) }}</span>
                        @if($load->type === 'LTL')
                            <div class="absolute top-4 right-4 bg-yellow-400 text-brand-black text-xs font-bold px-2 py-1 rounded">-40% OFF</div>
                        @endif
                        <h4 class="text-xl font-bold mt-6 mb-1">{{ $load->title ?? $load->merchant->name }}</h4>
                        <div class="flex items-center gap-1 mb-1">
                            <p class="text-sm opacity-90">{{ $load->merchant->name }}</p>
                            @if($load->merchant->tier)
                                <x-tier-badge :tier="$load->merchant->tier" class="scale-[0.7] origin-left" />
                            @endif
                            <span class="text-sm opacity-90">• {{ $load->item_name ?? 'Barang Umum' }}</span>
                        </div>
                        @if($load->min_driver_tier)
                            <div class="mt-2 text-xs font-medium bg-red-500/20 text-white rounded px-2 py-1 inline-block border border-red-500/50">
                                🔒 Hanya {{ ucfirst($load->min_driver_tier) }} ke atas
                            </div>
                        @endif
                    </div>

                    <!-- Body -->
                    <div class="p-6 flex-grow flex flex-col justify-between">
                        <div>
                            <div class="flex justify-between items-start mb-4">
                                <div>
                                    <p class="text-xs text-gray-500">Tipe Muatan</p>
                                    <p class="font-bold text-sm">{{ $load->type }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Status</p>
                                    <p class="font-bold text-sm uppercase {{ $load->status === 'open' ? 'text-green-600' : 'text-gray-600' }}">{{ $load->status }}</p>
                                </div>
                            </div>

                            <div class="mb-4">
                                <p class="text-xs text-gray-500 mb-1">Rute / Tujuan:</p>
                                <div class="flex flex-wrap gap-2">
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">📍 Dari: {{ $load->sender_address ?? 'Belum ditentukan' }}</span>
                                    <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">🏁 Ke: {{ $load->receiver_address ?? 'Belum ditentukan' }}</span>
                                    @if($load->distance)
                                        <span class="text-xs bg-blue-100 text-blue-800 px-2 py-1 rounded-full">Jarak: ~{{ $load->distance }} KM</span>
                                    @endif
                                </div>
                            </div>

                            <div class="bg-blue-50 rounded-xl p-4 mb-4 flex justify-between items-center">
                                <div>
                                    <p class="text-xs text-gray-500">Tarif Maksimal</p>
                                    <p class="font-bold text-brand-blue text-lg">Rp {{ number_format($load->max_price, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-xs text-gray-500">Sisa Slot / Berat</p>
                                    <p class="font-bold text-gray-900">{{ $load->type === 'LTL' ? ($load->available_weight ?? $load->weight_kg) : $load->weight_kg }} KG</p>
                                </div>
                            </div>

                            @if($load->bid_deadline && $load->status === 'open')
                            <div class="bg-gray-50 border border-gray-200 rounded-xl p-3 mb-4 flex justify-between items-center" x-data="{
                                deadline: new Date('{{ \Carbon\Carbon::parse($load->bid_deadline)->toIso8601String() }}').getTime(),
                                now: new Date().getTime(),
                                timeLeft: '',
                                init() {
                                    this.update();
                                    setInterval(() => this.update(), 1000);
                                },
                                update() {
                                    this.now = new Date().getTime();
                                    let distance = this.deadline - this.now;
                                    if (distance < 0) {
                                        this.timeLeft = 'Waktu Habis';
                                        return;
                                    }
                                    let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60)) + Math.floor(distance / (1000 * 60 * 60 * 24)) * 24;
                                    let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                                    let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                                    this.timeLeft = hours + 'j ' + minutes + 'm ' + seconds + 'd';
                                }
                            }">
                                <span class="text-xs font-bold text-gray-500">⏳ Sisa Waktu Bidding</span>
                                <span class="text-sm font-black text-brand-blue" x-text="timeLeft"></span>
                            </div>
                            @endif
                        </div>

                        <!-- Action -->
                        @if($load->status === 'open')
                            <div class="mt-4 pt-4 border-t border-gray-100">
                                <label class="block text-xs font-bold text-gray-700 mb-2">Penawaran Anda (Bid)</label>
                                <div class="flex space-x-2">
                                    <input type="number" wire:model="bid_amounts.{{ $load->id }}" placeholder="Cth: 800000" class="block w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm focus:ring-brand-blue focus:border-brand-blue text-sm px-4">
                                    <button wire:click="submitBid({{ $load->id }})" class="bg-brand-blue text-white px-6 py-2 rounded-xl hover:bg-blue-700 text-sm font-bold shadow-sm whitespace-nowrap transition-colors">Kirim Bid</button>
                                </div>
                                @error('bid_amounts.'.$load->id) <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                
                                <div class="mt-4 border-t border-gray-200 pt-4">
                                    <label class="block text-xs font-bold text-gray-700 mb-2">Tolak & Beri Saran Harga</label>
                                    <p class="text-[10px] text-gray-500 mb-2 leading-tight">Masukan ini bersifat wajib jika Anda menolak, ditujukan HANYA sebagai saran evaluasi untuk merchant agar harga bisa lebih menarik. Ini BUKAN penawaran bid.</p>
                                    <div class="flex space-x-2">
                                        <input type="number" wire:model="suggested_prices.{{ $load->id }}" placeholder="Saran Harga, Cth: 900000" class="block w-full border-gray-200 bg-gray-50 rounded-xl shadow-sm focus:ring-gray-400 focus:border-gray-400 text-sm px-4">
                                        <button wire:click="rejectBid({{ $load->id }})" class="bg-gray-200 text-gray-700 px-6 py-2 rounded-xl hover:bg-gray-300 text-sm font-bold shadow-sm whitespace-nowrap transition-colors">Tolak Bid</button>
                                    </div>
                                    @error('suggested_prices.'.$load->id) <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center text-gray-500 bg-white p-6 rounded-3xl border border-gray-100 shadow-sm">
                    Tidak ada muatan tersedia saat ini di Flash Market.
                </div>
            @endforelse
        </div>
    @endif
</div>
