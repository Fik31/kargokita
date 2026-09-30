<div class="py-12 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
        
        <!-- Profile Header Card (Glassmorphism Effect) -->
        <div class="relative overflow-hidden bg-white shadow-sm sm:rounded-3xl border border-gray-100 p-8 sm:p-12">
            <!-- Decorative background blobs -->
            <div class="absolute top-0 right-0 -mr-20 -mt-20 w-80 h-80 rounded-full bg-brand-blue/5 blur-3xl pointer-events-none"></div>
            <div class="absolute bottom-0 left-0 -ml-20 -mb-20 w-64 h-64 rounded-full bg-brand-tosca/5 blur-3xl pointer-events-none"></div>
            
            <div class="flex flex-col sm:flex-row items-center sm:items-start gap-8 relative z-10">
                <!-- Avatar -->
                <div class="relative group">
                    <div class="w-32 h-32 rounded-full overflow-hidden border-4 border-white shadow-xl ring-4 ring-gray-50">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=1D446F&color=fff&size=256" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    </div>
                    <div class="absolute -bottom-2 -right-2 bg-gradient-to-br from-brand-blue to-blue-800 text-white p-2 rounded-full shadow-lg border-2 border-white" title="User Tier">
                        @if($user->tier === 'vip')
                            <svg class="w-6 h-6 text-yellow-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"></path></svg>
                        @else
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        @endif
                    </div>
                </div>

                <!-- User Info -->
                <div class="flex-1 text-center sm:text-left">
                    <div class="flex flex-col sm:flex-row sm:items-center gap-3 mb-2">
                        <h1 class="text-3xl font-extrabold text-brand-black tracking-tight">
                            {{ $user->name }}
                        </h1>
                        <span class="inline-flex items-center px-4 py-1.5 rounded-full text-sm font-bold bg-blue-50 text-brand-blue border border-blue-100">
                            {{ ucfirst($user->roles->first()?->name ?? 'User') }}
                        </span>
                    </div>
                    
                    <p class="text-gray-500 mb-6 flex items-center justify-center sm:justify-start gap-2">
                        <svg class="w-4 h-4 text-brand-tosca" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                        Bergabung sejak {{ $user->created_at->translatedFormat('F Y') }}
                    </p>

                    <!-- Stats Row -->
                    <div class="grid grid-cols-2 gap-4 max-w-lg">
                        <div class="bg-gray-50/50 rounded-2xl p-5 shadow-sm border border-gray-100 transition-all hover:shadow-md">
                            <div class="text-sm font-semibold text-gray-500 mb-1">Tingkat Kesuksesan</div>
                            <div class="flex items-end gap-2">
                                <span class="text-3xl font-black text-brand-black">{{ $user->successful_delivery_percentage }}%</span>
                                <svg class="w-6 h-6 text-brand-tosca mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50/50 rounded-2xl p-5 shadow-sm border border-gray-100 transition-all hover:shadow-md">
                            <div class="text-sm font-semibold text-gray-500 mb-1">Rating Penilaian</div>
                            <div class="flex items-center gap-2">
                                <span class="text-3xl font-black text-brand-black">
                                    {{ number_format($user->ratingsAsRatee()->avg('rating') ?? 0, 1) }}
                                </span>
                                <div class="flex text-yellow-400">
                                    @for($i = 1; $i <= 5; $i++)
                                        <svg class="w-5 h-5 {{ $i <= round($user->ratingsAsRatee()->avg('rating') ?? 0) ? 'fill-current' : 'text-gray-200' }}" viewBox="0 0 20 20" fill="currentColor">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                        </svg>
                                    @endfor
                                </div>
                            </div>
                            <div class="text-xs text-gray-400 mt-1 font-medium">Dari {{ $user->ratingsAsRatee()->count() }} ulasan pengguna</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Role Specific Content -->
        @if($user->hasRole('merchant'))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 relative">
                <div class="h-1 w-full bg-brand-blue"></div>
                <div class="p-8">
                    <h2 class="text-xl font-bold text-brand-black mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-brand-blue" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                        Bidding Aktif Merchant
                    </h2>
                    
                    @if(count($activeBids) > 0)
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($activeBids as $bid)
                                <div class="bg-gray-50 rounded-2xl p-6 border border-gray-100 hover:shadow-lg transition-all group hover:border-brand-blue/30">
                                    <div class="flex justify-between items-start mb-4">
                                        <div class="bg-blue-100 text-brand-blue text-xs font-bold px-3 py-1 rounded-full border border-blue-200">
                                            {{ $bid->goods_type }}
                                        </div>
                                        <span class="text-sm font-black text-brand-black">Rp {{ number_format($bid->budget, 0, ',', '.') }}</span>
                                    </div>
                                    <div class="space-y-3 mb-6">
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-brand-blue"></div>
                                            <div>
                                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Asal</p>
                                                <p class="text-sm font-semibold text-gray-800">{{ $bid->origin }}</p>
                                            </div>
                                        </div>
                                        <div class="flex items-start gap-3">
                                            <div class="mt-1 flex-shrink-0 w-2 h-2 rounded-full bg-brand-tosca"></div>
                                            <div>
                                                <p class="text-[10px] text-gray-500 uppercase font-bold tracking-wider">Tujuan</p>
                                                <p class="text-sm font-semibold text-gray-800">{{ $bid->destination }}</p>
                                            </div>
                                        </div>
                                    </div>
                                    <a href="{{ route('bidding') }}" class="block w-full py-2.5 px-4 bg-white border-2 border-brand-blue text-brand-blue text-center rounded-xl font-bold transition-all group-hover:bg-brand-blue group-hover:text-white shadow-sm">
                                        Lihat & Ajukan Penawaran
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12 bg-gray-50/50 rounded-2xl border-2 border-dashed border-gray-200">
                            <div class="w-16 h-16 bg-white rounded-full flex items-center justify-center mx-auto mb-4 shadow-sm border border-gray-100">
                                <svg class="h-8 w-8 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                </svg>
                            </div>
                            <h3 class="text-base font-bold text-gray-900">Belum ada bidding aktif</h3>
                            <p class="mt-1 text-sm text-gray-500">Merchant ini sedang tidak mencari armada pengiriman.</p>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        @if($user->hasRole('driver'))
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 relative">
                <div class="h-1 w-full bg-brand-tosca"></div>
                <div class="p-8">
                    <h2 class="text-xl font-bold text-brand-black mb-6 flex items-center gap-2">
                        <svg class="w-6 h-6 text-brand-tosca" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17a2 2 0 11-4 0 2 2 0 014 0zM19 17a2 2 0 11-4 0 2 2 0 014 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16V6a1 1 0 00-1-1H4a1 1 0 00-1 1v10a1 1 0 001 1h1m8-1a1 1 0 01-1 1H9m4-1V8a1 1 0 011-1h2.586a1 1 0 01.707.293l3.414 3.414a1 1 0 01.293.707V16a1 1 0 01-1 1h-1m-6-1a1 1 0 001 1h1M5 17a2 2 0 104 0m-4 0a2 2 0 114 0m6 0a2 2 0 104 0m-4 0a2 2 0 114 0"></path></svg>
                        Informasi Kapasitas & Layanan Armada
                    </h2>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                        <!-- Jenis Armada Mockup -->
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-blue-100 flex items-center justify-center text-brand-blue flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Tipe Armada Utama</p>
                                <p class="text-base font-bold text-gray-900">Fuso Box / Wingbox</p>
                            </div>
                        </div>

                        <!-- Kapasitas Mockup -->
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-green-100 flex items-center justify-center text-green-600 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0-2v2m0 16V5m0 16H9m3 0h3"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Kapasitas Maksimal</p>
                                <p class="text-base font-bold text-gray-900">Hingga 10.000 KG (35 CBM)</p>
                            </div>
                        </div>
                        
                        <!-- Rute Favorit Mockup -->
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-purple-100 flex items-center justify-center text-purple-600 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Rute Operasional Sering</p>
                                <p class="text-base font-bold text-gray-900">Lintas Jawa (Barat - Timur)</p>
                            </div>
                        </div>

                        <!-- Status Ketersediaan Mockup -->
                        <div class="bg-gray-50 rounded-2xl p-5 border border-gray-100 flex items-center gap-4">
                            <div class="w-12 h-12 rounded-xl bg-orange-100 flex items-center justify-center text-orange-600 flex-shrink-0">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            </div>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase tracking-wider">Ketersediaan</p>
                                <p class="text-base font-bold text-gray-900">Siap Menerima Lelang</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-blue-50/50 rounded-2xl p-6 border border-blue-100 text-center relative overflow-hidden">
                         <div class="absolute inset-0 bg-[url('data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAiIGhlaWdodD0iMjAiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+PGNpcmNsZSBjeD0iMiIgY3k9IjIiIHI9IjIiIGZpbGw9IiNFMjU4MjIiIGZpbGwtb3BhY2l0eT0iMC4wNSIvPjwvc3ZnPg==')] opacity-50"></div>
                         <div class="relative z-10 flex flex-col sm:flex-row items-center justify-center gap-4 text-left">
                             <div class="w-12 h-12 bg-white rounded-full flex items-center justify-center flex-shrink-0 shadow-sm border border-blue-200">
                                <svg class="h-6 w-6 text-brand-tosca" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                             </div>
                             <div>
                                 <h4 class="text-sm font-bold text-gray-900">Informasi Pribadi & Plat Nomor Dilindungi</h4>
                                 <p class="text-gray-500 text-xs">Detail kendaraan (Plat, STNK, dll) dan kontak pribadi tidak ditampilkan untuk publik. Informasi tersebut hanya terbuka bagi Merchant ketika pengiriman sedang/akan berlangsung demi keamanan bersama.</p>
                             </div>
                         </div>
                    </div>
                </div>
            </div>
        @endif

    </div>

    <!-- Gallery Section -->
    @if($user->hasRole('driver') || $user->hasRole('merchant'))
        @php
            $galleries = $user->galleries()->latest()->get();
            $dummyImagesDriver = [
                ['url' => 'https://images.unsplash.com/photo-1601584115197-04ecc0da31d7?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'caption' => 'Truk Fuso Box Siap Jalan'],
                ['url' => 'https://images.unsplash.com/photo-1519003722824-194d4455a60c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'caption' => 'Kondisi Dalam Box Bersih'],
                ['url' => 'https://images.unsplash.com/photo-1581092160562-40aa08e78837?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'caption' => 'Peralatan Keselamatan APAR'],
                ['url' => 'https://images.unsplash.com/photo-1581092335397-9583eb92d232?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'caption' => 'Kelengkapan Terpal & Tali']
            ];
            $dummyImagesMerchant = [
                ['url' => 'https://images.unsplash.com/photo-1586528116311-ad8dd3c8310d?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'caption' => 'Loading Dock Area'],
                ['url' => 'https://images.unsplash.com/photo-1553413077-190dd305871c?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'caption' => 'Gudang Penyimpanan'],
                ['url' => 'https://images.unsplash.com/photo-1578575437130-527eed3abbec?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'caption' => 'Contoh Packing Pallet'],
                ['url' => 'https://images.unsplash.com/photo-1577705998148-6da4f3963bc8?ixlib=rb-4.0.3&auto=format&fit=crop&w=800&q=80', 'caption' => 'Akses Keluar Gudang']
            ];
            
            // Use real gallery if available, otherwise use dummy
            $hasRealGalleries = $galleries->count() > 0;
            $displayItems = $hasRealGalleries ? $galleries : ($user->hasRole('driver') ? $dummyImagesDriver : $dummyImagesMerchant);
        @endphp

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 mt-8 pb-12">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-3xl border border-gray-100 relative">
                <div class="h-1 w-full {{ $user->hasRole('driver') ? 'bg-brand-tosca' : 'bg-brand-blue' }}"></div>
                <div class="p-8">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-xl font-bold text-brand-black flex items-center gap-2">
                            <svg class="w-6 h-6 {{ $user->hasRole('driver') ? 'text-brand-tosca' : 'text-brand-blue' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                            Galeri {{ $user->hasRole('driver') ? 'Armada' : 'Fasilitas' }}
                        </h2>
                        @if(!$hasRealGalleries)
                            <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-3 py-1 rounded-full border border-yellow-200 shadow-sm">
                                Foto Ilustrasi (Sample)
                            </span>
                        @endif
                    </div>
                    
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                        @foreach($displayItems as $item)
                            <div class="group relative aspect-square rounded-2xl overflow-hidden border border-gray-200 shadow-sm bg-gray-50 cursor-pointer">
                                <img src="{{ $hasRealGalleries ? Storage::url($item->image_path) : $item['url'] }}" 
                                     alt="{{ $hasRealGalleries ? $item->caption : $item['caption'] }}" 
                                     class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-110">
                                
                                <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300 flex flex-col justify-end p-4">
                                    <p class="text-white text-sm font-bold truncate">
                                        {{ $hasRealGalleries ? ($item->caption ?? 'Tanpa Keterangan') : $item['caption'] }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
