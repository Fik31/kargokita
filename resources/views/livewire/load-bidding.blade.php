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
        <div id="tour-merchant-ad" class="bg-gradient-to-r from-gray-900 to-gray-800 rounded-2xl shadow-lg p-6 mb-8 flex flex-col md:flex-row items-center justify-between border border-gray-700">
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

        <div id="tour-merchant-form" class="bg-white p-6 rounded-2xl shadow-sm mb-8 border border-gray-100">
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
                                <option value="Blindvan">Blindvan - Box (700–800 kg | 3–4 CBM)</option>
                                <option value="Granmax / L300 Box">Granmax / L300 Box - Bak & Box (800–1.000 kg | 4–6 CBM)</option>
                                <option value="CDE">CDE - Bak & Box (2.000–2.500 kg | 6–9 CBM)</option>
                                <option value="CDE Long">CDE Long - Bak & Box (2.000–2.500 kg | 10–14 CBM)</option>
                                <option value="CDD">CDD - Bak & Box (4.000–5.000 kg | 13–18 CBM)</option>
                                <option value="CDD Long">CDD Long - Bak & Box (5.000–6.000 kg | 22–28 CBM)</option>
                                <option value="Fuso">Fuso - Bak & Box (8.000–10.000 kg | 25–35 CBM)</option>
                                <option value="Fuso Long / Wingbox">Fuso Long / Wingbox - Bak & Box (8.000–10.000 kg | 35–40 CBM)</option>
                                <option value="Tronton">Tronton - Bak & Box (15.000–20.000 kg | 40–50 CBM)</option>
                                <option value="Tronton Wingbox">Tronton Wingbox - Box (18.000–25.000 kg | 45–55 CBM)</option>
                                <option value="Trailer 20">Trailer 20" - Container (25.000 - 27.000 kg | 20 Feet)</option>
                                <option value="Trailer 40">Trailer 40" - Container (30.000 - 32.000 kg | 40 Feet)</option>
                            </select>
                            @error('vehicle_type_needed') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Perlengkapan Tambahan (Opsional)</label>
                            <input type="text" wire:model="required_equipments" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm" placeholder="Cth: Rompi Safety, Sepatu">
                            @error('required_equipments') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between mb-4 gap-4">
                            <h3 class="text-lg font-bold text-blue-800 flex items-center"><svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg> 2. Info Pengirim</h3>
                            <div class="flex items-center">
                                <input id="use_profile_data" type="checkbox" wire:model.live="use_profile_data" class="h-4 w-4 text-brand-blue focus:ring-brand-blue border-gray-300 rounded">
                                <label for="use_profile_data" class="ml-2 block text-sm font-medium text-gray-700">
                                    Gunakan Data Profil
                                </label>
                            </div>
                        </div>
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
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catatan Pengirim (Opsional)</label>
                                <textarea wire:model="sender_notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 sm:text-sm" placeholder="Contoh: Barang diambil di pos satpam..."></textarea>
                                @error('sender_notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
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
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Catatan Penerima (Opsional)</label>
                                <textarea wire:model="receiver_notes" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-green-500 focus:ring-green-500 sm:text-sm" placeholder="Contoh: Titip ke resepsionis atau hubungi sebelum sampai..."></textarea>
                                @error('receiver_notes') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Settings -->
                <div class="bg-yellow-50 p-4 rounded-xl border border-yellow-200 flex flex-col md:flex-row justify-between items-center gap-4">
                    @if(in_array(strtolower(Auth::user()->tier ?? ''), ['trusted', 'premium']))
                    <div class="flex items-center">
                        <input id="paylater" type="checkbox" wire:model="is_paylater" class="h-5 w-5 text-brand-blue focus:ring-brand-blue border-gray-300 rounded">
                        <label for="paylater" class="ml-3 block text-sm font-medium text-gray-800">
                            Gunakan Hak Eksklusif <span class="font-bold">(Cargo Fee)</span>
                        </label>
                    </div>
                    @endif
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Batas Bidding (Opsional):</label>
                        <input type="datetime-local" wire:model="bid_deadline" class="rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm">
                    </div>
                    @php
                        $mTier = strtolower(Auth::user()->tier ?? 'common');
                        $isVerifiedOrAbove = in_array($mTier, ['verified', 'trusted', 'premium']);
                    @endphp
                    @if($isVerifiedOrAbove)
                    <div class="flex items-center gap-2">
                        <label class="text-sm font-medium text-gray-700 whitespace-nowrap">Min. Tier Driver (Wajib Silver):</label>
                        <select wire:model="min_driver_tier" class="rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm">
                            <option value="silver">Silver ke atas</option>
                            <option value="gold">Hanya Gold</option>
                        </select>
                    </div>
                    @endif
                </div>

                @php
                    $feeStr = $mTier === 'verified' ? '10%' : (in_array($mTier, ['trusted', 'premium']) ? '20%' : '5%');
                    $slaStr = $mTier === 'verified' ? 'Priority' : (in_array($mTier, ['trusted', 'premium']) ? 'Premium & 24/7 Support' : 'Standard');
                    $truckSourcing = in_array($mTier, ['trusted', 'premium']) ? 'Notify All Driver & Priority Backup' : 'Menunggu di Bursa';
                @endphp
                <div class="flex flex-col md:flex-row justify-between items-center mt-6">
                    <div class="text-sm text-gray-500 bg-gray-50 px-4 py-2 rounded-lg border border-gray-200 mb-4 md:mb-0">
                        <span class="font-bold text-gray-700">Service Level Anda ({{ ucfirst($mTier) }}):</span> 
                        App Fee: <span class="font-bold text-brand-blue">{{ $feeStr }}</span> | 
                        SLA: <span class="font-bold text-brand-blue">{{ $slaStr }}</span> |
                        Sourcing: <span class="font-bold text-brand-blue">{{ $truckSourcing }}</span>
                    </div>
                    <div class="text-right">
                        <button type="submit" class="bg-brand-blue hover:bg-blue-700 text-white py-3 px-8 rounded-full shadow-lg font-bold text-lg transition-transform transform hover:scale-105">
                            Posting Order ke Bursa
                        </button>
                    </div>
                </div>
            </form>
        </div>

        @if(isset($recommendedDrivers) && $recommendedDrivers->count() > 0)
            <div id="tour-merchant-recommendation" class="mb-8 bg-gradient-to-r from-gray-50 to-white rounded-3xl p-6 border border-gray-200 shadow-sm">
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
                                <div class="flex items-center gap-1 hover:text-blue-500 transition-colors">
                                    <a href="{{ route('user.profile', $driver->id) }}" class="text-sm font-bold text-gray-900 truncate">{{ $driver->name }}</a>
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
        <div id="tour-bidding-header" class="flex justify-between items-center mb-6">
            <h3 class="text-xl font-bold text-brand-black uppercase tracking-wide">Flash Market (Bursa Muatan)</h3>
            <div class="w-64 hidden md:block">
                <input type="text" wire:model.live="search" placeholder="Cari merchant atau tipe..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm">
            </div>
        </div>

        <!-- FIXED TOP BOXES (Top Bid & Iklan) -->
        <div id="tour-bidding-topcards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- 3 Top Bids -->
            @for ($i = 0; $i < 3; $i++)
                @php
                    $merchant = $topBidMerchants[$i] ?? null;
                    // Dummy Cargo Data
                    $dummyTujuan = ['Jakarta - Surabaya', 'Bandung - Semarang', 'Cikarang - Karawang'];
                    $dummyBarang = ['Sparepart Motor', 'Elektronik', 'Kain Tekstil'];
                    $dummyHarga = [1500000, 850000, 450000];
                    $dummyBerat = [2500, 1000, 800];
                    $badges = ['TOP 1 BID TERBANYAK', 'TOP 1 TIER TERTINGGI', 'TOP 1 BID PILIHAN'];
                @endphp
                <a href="{{ $merchant ? route('user.profile', $merchant->id) : '#' }}" class="bg-white rounded-3xl shadow-sm overflow-hidden border-2 border-yellow-400 flex flex-col relative transform transition hover:scale-105 block">
                    <div class="absolute top-0 right-0 bg-yellow-400 text-xs font-bold px-3 py-1 rounded-bl-lg z-10">{{ $badges[$i] }}</div>
                    <div class="bg-gray-900 text-white p-5">
                        @if($merchant)
                            <h4 class="text-lg font-bold mt-2 truncate group-hover:text-blue-400 transition-colors">{{ $merchant->name }}</h4>
                            <p class="text-xs text-gray-400 truncate">{{ $dummyBarang[$i] }}</p>
                        @else
                            <h4 class="text-lg font-bold mt-2 truncate">Merchant Premium</h4>
                            <p class="text-xs text-gray-400 truncate">Muatan Menarik</p>
                        @endif
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
                </a>
            @endfor

            <!-- Iklan Carousel -->
            <div x-data="{
                activeSlide: 0,
                slides: {{ $activeAds->count() }},
                next() {
                    this.activeSlide = (this.activeSlide === this.slides - 1) ? 0 : this.activeSlide + 1;
                },
                prev() {
                    this.activeSlide = (this.activeSlide === 0) ? this.slides - 1 : this.activeSlide - 1;
                },
                init() {
                    if (this.slides > 1) {
                        setInterval(() => this.next(), 2000);
                    }
                }
            }" id="tour-bidding-ads" class="bg-gradient-to-br from-blue-900 to-blue-700 rounded-3xl shadow-lg overflow-hidden border-2 border-blue-500 flex flex-col relative transform transition hover:scale-105 text-white h-[320px] lg:h-auto">
                <div class="absolute top-0 right-0 bg-blue-500 text-white text-xs font-bold px-3 py-1 rounded-bl-lg z-20">IKLAN SPONSOR</div>
                
                @if($activeAds->count() > 0)
                    @php
                        $adTitles = [
                            "Tersedia banyak muatan setiap hari!",
                            "Dapatkan rute terbaik bersama kami!",
                            "Harga kompetitif, proses cepat!",
                            "Partner andalan logistik Anda.",
                            "Kirim barang lebih mudah & aman."
                        ];
                        $adSubtitles = [
                            "Gunakan fitur pencarian untuk menemukan muatan dari kami.",
                            "Kami siap melayani kebutuhan logistik perusahaan Anda.",
                            "Jadilah mitra pengemudi setia untuk muatan reguler.",
                            "Cek profil kami untuk melihat daftar rute yang tersedia.",
                            "Bergabunglah dan dapatkan pengalaman pengiriman terbaik."
                        ];
                    @endphp
                    <div class="relative w-full h-full flex-grow overflow-hidden">
                        @foreach($activeAds as $index => $ad)
                            <a href="{{ route('user.profile', $ad->id) }}" 
                               x-show="activeSlide === {{ $index }}"
                               x-transition:enter="transition ease-out duration-500"
                               x-transition:enter-start="opacity-0 transform translate-x-full"
                               x-transition:enter-end="opacity-100 transform translate-x-0"
                               x-transition:leave="transition ease-in duration-300 absolute"
                               x-transition:leave-start="opacity-100 transform translate-x-0"
                               x-transition:leave-end="opacity-0 transform -translate-x-full"
                               class="absolute inset-0 p-5 flex flex-col justify-center text-center">
                                
                                <div class="w-16 h-16 bg-white rounded-full mx-auto mb-4 flex items-center justify-center text-blue-900 font-bold text-2xl shadow-inner flex-shrink-0">
                                    {{ substr($ad->name, 0, 1) }}
                                </div>
                                <h4 class="text-lg font-bold mb-1 hover:text-blue-300 transition-colors">
                                    {{ $ad->name }}
                                </h4>
                                <p class="text-xs text-blue-200 mb-4">Mitra Terpercaya Kargokita</p>
                                
                                <div class="bg-white/20 rounded-xl p-3 backdrop-blur-sm">
                                    <p class="text-sm font-semibold">{{ $adTitles[$index % count($adTitles)] }}</p>
                                    <p class="text-xs mt-1">{{ $adSubtitles[$index % count($adSubtitles)] }}</p>
                                </div>
                            </a>
                        @endforeach
                    </div>
                    
                    <!-- Carousel Controls -->
                    @if($activeAds->count() > 1)
                        <button @click.prevent="prev()" class="absolute left-2 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 rounded-full p-2 z-30 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
                        </button>
                        <button @click.prevent="next()" class="absolute right-2 top-1/2 -translate-y-1/2 bg-white/20 hover:bg-white/40 rounded-full p-2 z-30 transition">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </button>
                        
                        <!-- Indicators -->
                        <div class="absolute bottom-3 left-0 right-0 flex justify-center space-x-2 z-30">
                            @foreach($activeAds as $index => $ad)
                                <button @click.prevent="activeSlide = {{ $index }}" :class="{'bg-white': activeSlide === {{ $index }}, 'bg-white/40': activeSlide !== {{ $index }}}" class="w-2 h-2 rounded-full transition-colors"></button>
                            @endforeach
                        </div>
                    @endif
                @else
                    <div class="p-5 flex-grow flex flex-col justify-center text-center border-2 border-dashed border-blue-400/50 m-2 rounded-2xl relative z-10">
                        <svg class="w-10 h-10 mx-auto text-blue-300 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        <h4 class="text-lg font-bold mb-1">Space Iklan Tersedia</h4>
                        <p class="text-xs text-blue-200">Hubungi Administrator untuk memasang iklan perusahaan Anda di sini.</p>
                    </div>
                @endif
            </div>
        </div>

        <div id="tour-bidding-list" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @forelse($loads as $load)
                <div class="bg-white rounded-3xl shadow-sm overflow-hidden border border-gray-100 flex flex-col">
                    <!-- Header -->
                    <div class="{{ $load->is_urgent ? 'bg-red-600' : 'bg-brand-blue' }} text-white p-6 relative">
                        <span class="bg-white {{ $load->is_urgent ? 'text-red-600' : 'text-brand-blue' }} text-xs font-bold px-2 py-1 rounded absolute top-4 left-4">FLS-{{ str_pad($load->id, 3, '0', STR_PAD_LEFT) }}</span>
                        @if($load->is_urgent)
                            <div class="absolute top-4 right-1/2 translate-x-1/2 bg-white text-red-600 text-xs font-bold px-3 py-1 rounded-full shadow-lg animate-pulse uppercase flex items-center"><svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>URGENT SOS</div>
                        @endif
                        @if($load->type === 'LTL')
                            <div class="absolute top-4 right-4 bg-yellow-400 text-brand-black text-xs font-bold px-2 py-1 rounded">-40% OFF</div>
                        @endif
                        <h4 class="text-xl font-bold mt-6 mb-1">{{ $load->title ?? $load->merchant->name }}</h4>
                        <div class="flex items-center gap-1 mb-1">
                            <a href="{{ route('user.profile', $load->merchant_id) }}" class="text-sm opacity-90 hover:text-blue-200 underline transition-colors">
                                {{ $load->merchant->name }}
                            </a>
                            @if($load->merchant->tier)
                                <x-tier-badge :tier="$load->merchant->tier" class="scale-[0.7] origin-left" />
                            @endif
                            <span class="text-sm opacity-90">• {{ $load->item_name ?? 'Barang Umum' }}</span>
                        </div>
                        @if($load->min_driver_tier && $load->min_driver_tier !== 'bronze')
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
                                    @if($load->required_equipments)
                                        <span class="text-xs bg-orange-100 text-orange-800 px-2 py-1 rounded-full flex items-center gap-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg> Wajib: {{ $load->required_equipments }}
                                        </span>
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
                                <p class="text-[10px] text-gray-500 mb-2 leading-tight">Pastikan harga penawaran Anda <strong>lebih rendah</strong> dari tarif maksimal (Rp {{ number_format($load->max_price, 0, ',', '.') }}) yang ditetapkan merchant.</p>
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

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/driver.js@1.0.1/dist/driver.js.iife.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const isCommonUser = @json(!Auth::user()->hasRole('administrator') && !Auth::user()->hasRole('hse') && !Auth::user()->hasRole('merchant') && !Auth::user()->hasRole('driver'));
        const isDriverUser = @json(Auth::user()->hasRole('driver'));
        
        // UNTUK PRESENTASI: Nonaktifkan cek localStorage
        // const hasSeenTour = localStorage.getItem('hasSeenTour_bidding_common');

        if (isCommonUser || isDriverUser) {
            const driver = window.driver.js.driver;
            
            let steps = [
                {
                    element: '#tour-bidding-header',
                    popover: {
                        title: 'Flash Market',
                        description: 'Ini adalah halaman bursa muatan, tempat Anda bisa melihat seluruh order pengiriman yang tersedia.',
                        side: "bottom",
                        align: 'start'
                    }
                }
            ];

            if (isDriverUser) {
                // Tambahkan langkah spesifik untuk driver
                steps.push(
                    {
                        element: '#tour-bidding-topcards',
                        popover: {
                            title: 'Rekomendasi Merchant (Black Card)',
                            description: 'Ketiga kartu hitam ini adalah Merchant unggulan kami. Ada Top 1 Bid Terbanyak, Top 1 Tier Tertinggi, dan Top 1 Bid Pilihan. Anda dapat memprioritaskan muatan dari mereka.',
                            side: "bottom",
                            align: 'start'
                        }
                    },
                    {
                        element: '#tour-bidding-ads',
                        popover: {
                            title: 'Iklan Sponsor',
                            description: 'Di bagian ini Anda akan melihat iklan dari Merchant yang membutuhkan driver secara reguler atau memiliki promo khusus. Klik untuk melihat profil mereka.',
                            side: "bottom",
                            align: 'start'
                        }
                    },
                    {
                        element: '#tour-bidding-list',
                        popover: {
                            title: 'Jenis-Jenis Muatan',
                            description: 'Di bawah ini adalah daftar muatan yang tersedia. Anda mungkin akan melihat label khusus seperti: <br><br><b>URGENT SOS (Merah)</b>: Muatan ini butuh driver darurat secepatnya.<br><b>Hanya Silver ke atas</b>: Muatan eksklusif yang dibatasi oleh merchant untuk tier tertentu.<br><b>Bid Normal</b>: Terbuka untuk semua driver.',
                            side: "top",
                            align: 'center'
                        }
                    },
                    {
                        element: '#tour-bidding-list',
                        popover: {
                            title: 'Informasi Detail Muatan',
                            description: 'Pada setiap kartu muatan, Anda bisa melihat <b>Tipe Muatan</b> (FTL/LTL), <b>Rute Tujuan</b> (dari mana ke mana), dan <b>Tarif Maksimal</b> yang bersedia dibayarkan oleh Merchant.',
                            side: "top",
                            align: 'center'
                        }
                    },
                    {
                        element: '#tour-bidding-list',
                        popover: {
                            title: 'Cara Melakukan Bidding',
                            description: 'Jika muatan dirasa cocok, Anda bisa memasukkan harga penawaran Anda di kolom <b>Bid</b> (pastikan lebih rendah dari tarif maksimal merchant) dan klik <b>Kirim Bid</b>.<br><br>Jika Anda merasa harganya tidak masuk akal, Anda bisa mengisi saran harga dan klik <b>Tolak Bid</b> agar sistem memberitahu merchant.',
                            side: "top",
                            align: 'center'
                        }
                    }
                );
            } else {
                // Langkah untuk common
                steps.push({
                    element: '#tour-bidding-list',
                    popover: {
                        title: 'Daftar Muatan',
                        description: 'Di sini akan tampil daftar muatan. Namun, karena Anda masih akun Common, fitur penawaran (bidding) dibatasi sampai Anda terverifikasi sebagai Driver.',
                        side: "top",
                        align: 'center'
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
                        // localStorage.setItem('hasSeenTour_bidding_common', 'true');
                        driverObj.destroy();
                    }
                },
            });
            
            setTimeout(() => {
                driverObj.drive();
            }, 500);
        } else if (isMerchantUser) {
            const driver = window.driver.js.driver;
            
            let steps = [
                {
                    element: '#tour-merchant-form',
                    popover: {
                        title: 'Buat Order Muatan',
                        description: 'Di sini Anda dapat mengisi detail muatan, menentukan tarif maksimal, serta memilih tipe layanan (FTL atau LTL).',
                        side: "bottom",
                        align: 'center'
                    }
                }
            ];

            if (document.querySelector('#tour-merchant-ad')) {
                steps.unshift({
                    element: '#tour-merchant-ad',
                    popover: {
                        title: 'Tingkatkan Exposure',
                        description: 'Gunakan fitur Iklan agar muatan Anda tampil di baris teratas Flash Market dan lebih cepat diambil driver.',
                        side: "bottom",
                        align: 'center'
                    }
                });
            }

            if (document.querySelector('#tour-merchant-recommendation')) {
                steps.push({
                    element: '#tour-merchant-recommendation',
                    popover: {
                        title: 'Rekomendasi Driver',
                        description: 'Kami merekomendasikan beberapa driver terbaik di sekitar area Anda. Anda bisa langsung menghubungi mereka.',
                        side: "top",
                        align: 'center'
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
