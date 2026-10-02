<div class="p-6">
    <div class="max-w-3xl mx-auto bg-white rounded-xl shadow-sm border border-gray-100 p-8">
        <h2 class="text-2xl font-bold text-gray-900 mb-6">Buat Quick Bid (Instant Order)</h2>

        @if(!$isVerified)
            <!-- Verifikasi Form (Bisa langsung disubmit kosong) -->
            <div class="p-6 bg-yellow-50 border border-yellow-200 rounded-xl space-y-4">
                <div class="flex items-start gap-4">
                    <div class="p-2 bg-yellow-100 rounded-full text-yellow-600 shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                    </div>
                    <div>
                        <h3 class="font-bold text-yellow-800 text-lg">Verifikasi Akun Diperlukan</h3>
                        <p class="text-yellow-700 mt-1">Sesuai regulasi keamanan, Anda diwajibkan untuk mengunggah foto KTP dan foto diri sebelum dapat membuat permintaan pengiriman.</p>
                    </div>
                </div>

                <form wire:submit.prevent="verifyAccount" class="mt-4 space-y-4 bg-white p-4 rounded-lg border border-yellow-100 shadow-sm">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Upload Foto KTP (Opsional)</label>
                        <input type="file" wire:model="ktp_photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Upload Foto Diri (Opsional)</label>
                        <input type="file" wire:model="selfie_photo" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                    </div>

                    <button type="submit" wire:loading.attr="disabled" class="w-full bg-yellow-500 hover:bg-yellow-600 text-white font-bold py-2 px-4 rounded-lg transition-all disabled:opacity-50">
                        Kirim Verifikasi Sekarang
                    </button>
                </form>
            </div>
        @elseif($this->activeLoad)
            <!-- Active Order Status (Live Polling) -->
            <div wire:poll.2s="checkStatus" class="p-6 bg-blue-50 border border-blue-200 rounded-xl">
                <div class="flex justify-between items-start mb-4">
                    <div>
                        <h3 class="font-bold text-blue-900 text-xl">{{ $this->activeLoad->title }}</h3>
                        <p class="text-blue-700 mt-1 text-sm">{{ $this->activeLoad->sender_address }} <span class="mx-2">&rarr;</span> {{ $this->activeLoad->receiver_address }}</p>
                    </div>
                    @if($this->activeLoad->status === 'open')
                        <span class="px-4 py-1.5 bg-blue-600 text-white text-sm font-bold rounded-full animate-pulse shadow-sm">
                            Mencari Driver...
                        </span>
                    @else
                        <span class="px-4 py-1.5 bg-green-500 text-white text-sm font-bold rounded-full shadow-sm">
                            Driver Ditemukan!
                        </span>
                    @endif
                </div>

                <div class="bg-white rounded-lg p-5 border border-blue-100 mt-4 shadow-sm">
                    @if($this->activeLoad->status === 'open')
                        <div class="flex items-center justify-center py-8 flex-col text-center">
                            <svg class="w-12 h-12 text-blue-300 animate-spin mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                            <p class="text-gray-500 font-medium">Sistem sedang menyiarkan pesanan Anda ke seluruh driver terdekat.</p>
                            <button wire:click="cancelOrder" class="mt-4 text-red-500 text-sm font-bold hover:underline">Batalkan Pencarian</button>
                        </div>
                    @elseif($this->activeLoad->trip && $this->activeLoad->trip->driver)
                        <!-- Tampilan Driver Ditemukan -->
                        <div class="flex items-center gap-4 border-b border-gray-100 pb-4 mb-4">
                            <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center text-green-700 font-bold text-2xl shrink-0 border-2 border-green-200">
                                {{ substr($this->activeLoad->trip->driver->name, 0, 1) }}
                            </div>
                            <div>
                                <h4 class="font-bold text-gray-900 text-lg">{{ $this->activeLoad->trip->driver->name }}</h4>
                                <p class="text-green-600 font-bold text-sm mt-0.5 animate-pulse">Driver segera menuju ke alamat mu!</p>
                                <div class="mt-2 text-sm font-bold text-gray-900 bg-gray-50 inline-block px-3 py-1 rounded-md">
                                    Harga Kesepakatan: Rp {{ number_format($this->activeLoad->max_price, 0, ',', '.') }}
                                </div>
                            </div>
                        </div>
                        
                        <div class="text-center">
                            <button wire:click="createNewOrder" class="w-full sm:w-auto px-6 py-2 bg-gray-900 hover:bg-gray-800 text-white text-sm font-bold rounded-lg shadow-sm transition-all">
                                Buat Quick Bid Lain
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @else
            <!-- Order Form -->
            <form wire:submit.prevent="submitOrder" class="space-y-6">
                @if (session()->has('message'))
                    <div class="p-3 bg-green-100 text-green-700 rounded-lg font-medium">
                        {{ session('message') }}
                    </div>
                @endif

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Penjemputan (Asal)</label>
                    <textarea wire:model="sender_address" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Jl. Raya Asal No.1, Jakarta"></textarea>
                    @error('sender_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Alamat Pengiriman (Tujuan)</label>
                    <textarea wire:model="receiver_address" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Jl. Raya Tujuan No.2, Bandung"></textarea>
                    @error('receiver_address') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Harga / Budget (Rp)</label>
                        <input type="number" wire:model="max_price" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="150000">
                        @error('max_price') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-sm font-bold text-gray-700 mb-1">Judul Pekerjaan</label>
                        <input type="text" wire:model="title" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Pindah Rumah 1 Kamar">
                        @error('title') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-1">Deskripsi Barang (FTL)</label>
                    <input type="text" wire:model="item_name" class="w-full border-gray-300 rounded-lg shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Kasur, Lemari, Kardus">
                    @error('item_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>

                <button type="submit" wire:loading.attr="disabled" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg shadow-md transition-all disabled:opacity-50">
                    <span wire:loading.remove wire:target="submitOrder">Buat Quick Bid Sekarang</span>
                    <span wire:loading wire:target="submitOrder">Memproses...</span>
                </button>
            </form>
        @endif
    </div>
</div>
