<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    @if (session()->has('message'))
        <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
            {{ session('message') }}
        </div>
    @endif

    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4">
        <h2 class="text-2xl font-bold text-gray-900 uppercase tracking-wide">Daftar Order Anda</h2>
        <div class="flex items-center gap-2">
            <button wire:click="setTab('open')" class="px-4 py-2 text-sm font-bold rounded-lg transition {{ $activeTab === 'open' ? 'bg-brand-blue text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Baru Dibuka</button>
            <button wire:click="setTab('waiting')" class="px-4 py-2 text-sm font-bold rounded-lg transition {{ $activeTab === 'waiting' ? 'bg-brand-blue text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Menunggu Persetujuan</button>
            <button wire:click="setTab('completed')" class="px-4 py-2 text-sm font-bold rounded-lg transition {{ $activeTab === 'completed' ? 'bg-brand-blue text-white shadow-md' : 'bg-white text-gray-600 border border-gray-200 hover:bg-gray-50' }}">Berjalan & Selesai</button>
        </div>
    </div>
    
    <div class="w-full mb-6">
        <input type="text" wire:model.live="search" placeholder="Cari order..." class="w-full md:w-1/3 rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm">
    </div>
    
    <div class="space-y-6">
        @forelse($loads as $load)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mb-6 transition-all hover:shadow-md">
                <!-- Load Header -->
                <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
                    <div>
                        <div class="flex flex-wrap items-center gap-3 mb-2">
                            <span class="px-3 py-1 bg-gray-100 text-gray-700 text-xs font-bold rounded-full border border-gray-200">
                                ID: {{ $load->id }}
                            </span>
                            <span class="px-3 py-1 {{ $load->status === 'open' ? 'bg-green-100 text-green-700 border-green-200' : 'bg-gray-100 text-gray-600 border-gray-200' }} text-xs font-bold rounded-full border uppercase">
                                {{ $load->status }}
                            </span>
                            <span class="px-3 py-1 {{ $load->type === 'FTL' ? 'bg-blue-100 text-blue-700 border-blue-200' : 'bg-purple-100 text-purple-700 border-purple-200' }} text-xs font-bold rounded-full border">
                                {{ $load->type === 'FTL' ? 'FTL - Full Truckload (Sewa Penuh)' : 'LTL - Sharing Muatan' }}
                            </span>
                        </div>
                        <h4 class="text-xl font-bold text-gray-900">{{ $load->title ?? 'Pengiriman Barang' }}</h4>
                        <p class="text-sm text-gray-500 mt-1">Barang: {{ $load->item_name }} • Kendaraan: {{ $load->vehicle_type_needed }}</p>
                    </div>
                    <div class="text-left md:text-right">
                        <p class="text-xs text-gray-500 font-medium mb-1">Budget / Tarif Maksimal</p>
                        <p class="text-2xl font-black text-brand-blue">Rp {{ number_format($load->max_price, 0, ',', '.') }}</p>
                        @if($load->type === 'LTL' && $load->weight_kg > 0)
                            <p class="text-[10px] text-gray-400 mt-1">Est. Rp {{ number_format($load->max_price / $load->weight_kg, 0, ',', '.') }} / kg</p>
                        @endif
                    </div>
                </div>

                <!-- Route & Details -->
                <div class="bg-gray-50 p-6 flex flex-col md:flex-row gap-6 border-b border-gray-100">
                    <!-- Route Timeline -->
                    <div class="flex-1">
                        <h5 class="text-xs font-bold text-gray-400 uppercase tracking-wider mb-4">Rute Pengiriman</h5>
                        <div class="relative pl-6 space-y-4 before:content-[''] before:absolute before:left-2.5 before:top-2 before:bottom-2 before:w-0.5 before:bg-gray-300">
                            <div class="relative">
                                <div class="absolute -left-6 top-1 w-3 h-3 bg-white border-2 border-brand-blue rounded-full"></div>
                                <p class="text-xs text-gray-500 font-semibold uppercase">Asal</p>
                                <p class="text-sm font-bold text-gray-800">{{ $load->sender_address ?? 'Belum ditentukan' }}</p>
                            </div>
                            <div class="relative">
                                <div class="absolute -left-6 top-1 w-3 h-3 bg-brand-blue rounded-full shadow-[0_0_0_3px_rgba(37,99,235,0.2)]"></div>
                                <p class="text-xs text-gray-500 font-semibold uppercase">Tujuan</p>
                                <p class="text-sm font-bold text-gray-800">{{ $load->receiver_address ?? 'Belum ditentukan' }}</p>
                            </div>
                        </div>
                    </div>

                    <!-- Metric Boxes -->
                    <div class="flex-1 grid grid-cols-2 gap-4">
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-center items-center text-center">
                            <span class="text-xs text-gray-500 font-medium mb-1">Berat Total Muatan</span>
                            <span class="text-lg font-bold text-gray-900">{{ $load->weight_kg }} <span class="text-sm text-gray-500 font-normal">KG</span></span>
                        </div>
                        <div class="bg-white p-4 rounded-xl border border-gray-200 shadow-sm flex flex-col justify-center items-center text-center">
                            <span class="text-xs text-gray-500 font-medium mb-1">Total Penawaran Masuk</span>
                            <span class="text-lg font-bold text-brand-blue">{{ $load->bids->whereIn('status', ['pending', 'accepted'])->count() }} <span class="text-sm text-gray-500 font-normal">Bid</span></span>
                        </div>
                    </div>
                </div>

                <!-- Bids Section -->
                @if(in_array($load->status, ['open', 'in_transit', 'closed']))
                <div class="p-6 space-y-6">
                    @php
                        $activeBids = $load->bids->whereIn('status', ['pending', 'accepted']);
                        $rejectedBids = $load->bids->where('status', 'rejected');
                    @endphp

                    @if($load->bid_deadline && $load->status === 'open')
                    <div class="bg-blue-50 rounded-xl p-4 flex items-center justify-between border border-blue-100" x-data="{
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
                            let days = Math.floor(distance / (1000 * 60 * 60 * 24));
                            let hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
                            let minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                            let seconds = Math.floor((distance % (1000 * 60)) / 1000);
                            this.timeLeft = (days > 0 ? days + 'h ' : '') + hours + 'j ' + minutes + 'm ' + seconds + 'd';
                        }
                    }">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 text-brand-blue mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <span class="text-sm font-bold text-blue-900">Sisa Waktu Bidding:</span>
                        </div>
                        <div class="text-lg font-black text-brand-blue tracking-wider" x-text="timeLeft"></div>
                    </div>
                    @endif

                    <div>
                        <h5 class="text-sm font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2z"></path></svg>
                            Daftar Penawaran Masuk (Terendah ke Tertinggi)
                        </h5>
                        
                        @if($activeBids->count() > 0)
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                @foreach($activeBids as $bid)
                                    <div class="bg-white border border-brand-blue/30 rounded-xl p-4 flex flex-col gap-3 shadow-sm hover:border-brand-blue transition-colors">
                                        <div class="flex justify-between items-start">
                                            <div class="flex items-center gap-3">
                                                <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center text-gray-500 font-bold border border-gray-200">
                                                    {{ substr($bid->driver->name, 0, 1) }}
                                                </div>
                                                <div>
                                                    <div class="flex items-center gap-1">
                                                        <h6 class="font-bold text-gray-900 text-sm leading-tight hover:text-blue-500 transition-colors">
                                                            <a href="{{ route('user.profile', $bid->driver_id) }}">{{ $bid->driver->name }}</a>
                                                        </h6>
                                                        @if($bid->driver->tier)
                                                            <x-tier-badge :tier="$bid->driver->tier" class="scale-[0.8] origin-left" />
                                                        @endif
                                                    </div>
                                                    <div class="flex items-center gap-2 mt-1">
                                                        <div class="flex items-center text-xs font-medium text-yellow-600 bg-yellow-50 px-1.5 py-0.5 rounded border border-yellow-100">
                                                            <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                                                            {{ number_format($bid->driver->ratings_as_ratee_avg_score ?? 0, 1) }}
                                                        </div>
                                                        <div class="flex items-center text-xs font-medium px-1.5 py-0.5 rounded border {{ $bid->driver->successful_delivery_percentage >= 80 ? 'text-green-700 bg-green-50 border-green-200' : 'text-orange-700 bg-orange-50 border-orange-200' }}">
                                                            Sukses: {{ $bid->driver->successful_delivery_percentage }}%
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @if($bid->status === 'accepted')
                                                <span class="bg-green-500 text-white text-[10px] font-bold px-2 py-1 rounded uppercase tracking-wider">Diterima</span>
                                            @endif
                                        </div>
                                        
                                        <div class="bg-blue-50 p-3 rounded-lg border border-blue-100 flex justify-between items-center mt-auto">
                                            <div>
                                                <p class="text-[10px] text-gray-500 uppercase font-semibold">Nilai Penawaran</p>
                                                <p class="text-base font-black text-brand-blue">Rp {{ number_format($bid->amount, 0, ',', '.') }}</p>
                                            </div>
                                            @if($bid->status === 'pending')
                                                <button wire:click="approveBid({{ $bid->id }})" class="bg-brand-blue hover:bg-blue-700 text-white text-xs font-bold py-2 px-4 rounded-lg shadow-sm transition-transform transform hover:-translate-y-0.5">Pilih Driver</button>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="bg-gray-50 rounded-xl p-8 text-center border border-dashed border-gray-300">
                                <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-blue-50 text-brand-blue mb-3">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                </div>
                                <p class="text-gray-500 font-medium">Belum ada penawaran masuk dari driver.</p>
                                <p class="text-sm text-gray-400 mt-1">Silakan tunggu beberapa saat, sistem sedang mendistribusikan order Anda.</p>
                            </div>
                        @endif
                    </div>

                    @if($rejectedBids->count() > 0)
                    <div class="mt-4 pt-6 border-t border-gray-200">
                        <h5 class="text-sm font-bold text-gray-800 mb-4 flex items-center">
                            <svg class="w-4 h-4 mr-2 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7a4 4 0 11-8 0 4 4 0 018 0zM9 14a6 6 0 00-6 6v1h12v-1a6 6 0 00-6-6zM21 12h-6"></path></svg>
                            Histori Penolakan & Saran Harga
                        </h5>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            @foreach($rejectedBids as $bid)
                                <div class="bg-gray-50 border border-gray-200 rounded-xl p-4 flex flex-col gap-2">
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="w-8 h-8 rounded-full bg-gray-200 flex items-center justify-center text-gray-500 font-bold text-xs">
                                            {{ substr($bid->driver->name, 0, 1) }}
                                        </div>
                                        <div>
                                            <h6 class="font-bold text-gray-700 text-xs hover:text-blue-500 transition-colors">
                                                <a href="{{ route('user.profile', $bid->driver_id) }}">{{ $bid->driver->name }}</a>
                                            </h6>
                                            <span class="bg-gray-200 text-gray-500 text-[9px] font-bold px-1.5 py-0.5 rounded uppercase">Menolak Bid</span>
                                        </div>
                                    </div>
                                    <div class="bg-white p-2 rounded border border-gray-100">
                                        <p class="text-[10px] text-gray-500 uppercase font-semibold">Saran Harga</p>
                                        <p class="text-sm font-bold text-red-600">Rp {{ number_format($bid->suggested_price, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    @endif
                    
                    @if($load->status === 'closed')
                        <div class="mt-6 text-center md:text-right">
                            <button wire:click="repostLoad({{ $load->id }})" class="bg-yellow-400 hover:bg-yellow-500 text-gray-900 text-sm font-bold py-2.5 px-6 rounded-xl shadow-sm transition-colors">
                                Buka Kembali Order (Repost)
                            </button>
                        </div>
                    @endif
                </div>
                @endif
            </div>
        @empty
            <div class="text-center text-gray-500 bg-white p-6 rounded-lg shadow">Tidak ada order Anda saat ini.</div>
        @endforelse
    </div>
</div>
