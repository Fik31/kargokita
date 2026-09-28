<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="flex flex-col lg:flex-row gap-8">
        
        <!-- CENTER COLUMN: FEED (Main Content) -->
        <div class="flex-1 lg:w-2/3 max-w-2xl w-full mx-auto lg:mx-0 space-y-6">

            <!-- Stories Section -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <div class="flex space-x-4 overflow-x-auto pb-2 scrollbar-hide">
                    <!-- Add Story Button (Dummy) -->
                    <div class="flex flex-col items-center flex-shrink-0 cursor-pointer">
                        <div class="w-16 h-16 rounded-full p-0.5 bg-gray-100 flex items-center justify-center relative">
                            <img src="https://ui-avatars.com/api/?name=You&background=fff" class="w-full h-full rounded-full border-2 border-white object-cover">
                            <div class="absolute bottom-0 right-0 bg-brand-blue text-white rounded-full w-5 h-5 flex items-center justify-center border-2 border-white text-xs font-bold shadow-sm">+</div>
                        </div>
                        <span class="text-xs text-gray-600 mt-2 font-medium">Buat Story</span>
                    </div>

                    <!-- Story Items -->
                    @foreach($stories as $story)
                    <div class="flex flex-col items-center flex-shrink-0 cursor-pointer group" wire:click="viewStory({{ $story['id'] }})">
                        <div class="w-16 h-16 rounded-full p-0.5 bg-gradient-to-tr from-yellow-400 via-orange-400 to-brand-blue flex items-center justify-center group-hover:scale-105 transition transform">
                            <img src="{{ $story['avatar'] }}" class="w-full h-full rounded-full border-2 border-white object-cover">
                        </div>
                        <span class="text-xs text-gray-700 mt-2 font-medium truncate w-16 text-center">{{ strtok($story['user'], ' ') }}</span>
                    </div>
                    @endforeach
                </div>
            </div>

            <!-- Form Create Post -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800 mb-4">Buat Postingan Baru</h3>
                @if (session()->has('message'))
                    <div class="p-3 mb-4 text-sm text-green-800 rounded-lg bg-green-50 border border-green-100">
                        {{ session('message') }}
                    </div>
                @endif
                @if (session()->has('error'))
                    <div class="p-3 mb-4 text-sm text-blue-800 rounded-lg bg-blue-50 border border-blue-100">
                        {{ session('error') }}
                    </div>
                @endif
                <form wire:submit.prevent="createPost">
                    <div class="mb-4 flex items-start space-x-3">
                        <img src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=0D8ABC&color=fff" class="w-10 h-10 rounded-full shadow-sm flex-shrink-0 mt-1">
                        <div class="flex-1">
                            <textarea wire:model="content" rows="3" class="w-full border border-gray-100 rounded-xl focus:border-brand-blue focus:ring focus:ring-brand-blue focus:ring-opacity-50 resize-none bg-gray-50 p-4 text-gray-700 placeholder-gray-400" placeholder="Bagikan info muatan, perjalanan, atau diskusi dengan komunitas..."></textarea>
                            @error('content') <span class="text-red-500 text-xs px-2">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="flex items-center justify-between border-t border-gray-100 pt-4 mt-2">
                        <div class="flex items-center space-x-2">
                            <label class="cursor-pointer flex items-center text-sm font-medium text-gray-600 hover:bg-gray-100 px-3 py-2 rounded-lg transition">
                                <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <span>Foto</span>
                                <input type="file" wire:model="image" class="hidden">
                            </label>
                            @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                            
                            <select wire:model="type" class="border-gray-200 text-gray-600 rounded-lg shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue focus:ring-opacity-50 text-sm bg-gray-50 font-medium">
                                <option value="status">Status Umum</option>
                                <option value="seeking_load">Cari Muatan</option>
                                <option value="seeking_driver">Cari Driver</option>
                            </select>
                        </div>
                        <button type="submit" class="bg-brand-blue hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-full shadow-sm transition">
                            Post
                        </button>
                    </div>
                </form>
            </div>

            <!-- Feed List -->
            <div class="space-y-6">
                @forelse ($posts as $post)
                    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center space-x-3">
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($post->user->name) }}&background=random" class="w-12 h-12 rounded-full border border-gray-100 shadow-sm">
                                <div>
                                    <div class="font-bold text-gray-900 flex items-center">
                                        {{ $post->user->name }}
                                        @if($post->user->is_subscribed)
                                            <svg class="w-4 h-4 text-blue-500 ml-1" fill="currentColor" viewBox="0 0 20 20"><title>VIP Member</title><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                                        @endif
                                    </div>
                                    <div class="text-xs text-gray-500 font-medium">{{ $post->created_at->diffForHumans() }}</div>
                                </div>
                            </div>
                            <span class="px-3 py-1 text-[10px] rounded-full uppercase tracking-wider font-bold {{ $post->type === 'status' ? 'bg-gray-100 text-gray-600' : 'bg-blue-50 text-brand-blue border border-blue-100' }}">
                                {{ str_replace('_', ' ', $post->type) }}
                            </span>
                        </div>
                        <div class="text-gray-800 whitespace-pre-wrap text-sm leading-relaxed mb-4">{{ $post->content }}</div>
                        @if($post->image)
                            <div class="mb-4 rounded-xl overflow-hidden border border-gray-100 bg-gray-50">
                                <img src="{{ Storage::url($post->image) }}" alt="Post image" class="w-full max-h-[500px] object-cover">
                            </div>
                        @endif
                        <div class="pt-4 border-t border-gray-100 flex items-center space-x-6 text-gray-500">
                            <button wire:click="likePost({{ $post->id }})" class="flex items-center hover:text-brand-blue transition group">
                                <div class="p-2 rounded-full group-hover:bg-blue-50 transition">
                                    <svg class="w-5 h-5 {{-- Add color if liked in future --}}" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                                </div>
                                <span class="text-sm font-medium ml-1">Suka</span>
                            </button>
                            <button class="flex items-center hover:text-brand-blue transition group">
                                <div class="p-2 rounded-full group-hover:bg-blue-50 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                                </div>
                                <span class="text-sm font-medium ml-1">Komentar</span>
                            </button>
                            <button class="flex items-center hover:text-brand-blue transition group ml-auto">
                                <div class="p-2 rounded-full group-hover:bg-blue-50 transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.684 13.342C8.886 12.938 9 12.482 9 12c0-.482-.114-.938-.316-1.342m0 2.684a3 3 0 110-2.684m0 2.684l6.632 3.316m-6.632-6l6.632-3.316m0 0a3 3 0 105.367-2.684 3 3 0 00-5.367 2.684zm0 9.316a3 3 0 105.368 2.684 3 3 0 00-5.368-2.684z"></path></svg>
                                </div>
                            </button>
                        </div>
                    </div>
                @empty
                    <div class="text-center text-gray-500 bg-white p-12 rounded-2xl shadow-sm border border-gray-100">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1m2 13a2 2 0 01-2-2V7m2 13a2 2 0 002-2V9.5a2.5 2.5 0 00-2.5-2.5H15M9 11l3 3m0 0l3-3m-3 3V8"></path></svg>
                        <p class="text-lg font-medium text-gray-900">Belum ada aktivitas</p>
                        <p class="text-sm mt-1">Jadilah yang pertama membuat status di komunitas!</p>
                    </div>
                @endforelse
            </div>
            
        </div>

        <!-- RIGHT COLUMN: WIDGETS -->
        <div class="hidden lg:block lg:w-1/3">
            <div class="sticky top-8 space-y-6">
            
            <!-- Driver Ranking Widget -->
            @if($topDrivers->isNotEmpty())
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center justify-between pb-2 border-b border-gray-50">
                    <span>Top Drivers</span>
                    <span class="text-xs text-brand-blue font-semibold bg-blue-50 px-2 py-1 rounded">Trips</span>
                </h3>
                <div class="space-y-3">
                    @foreach($topDrivers as $index => $driver)
                        <div class="flex items-center justify-between p-2 hover:bg-gray-50 rounded-lg transition cursor-pointer">
                            <div class="flex items-center space-x-3">
                                <span class="text-sm font-bold {{ $index < 3 ? 'text-gray-900' : 'text-gray-400' }} w-4">{{ $index + 1 }}.</span>
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=random" class="w-9 h-9 rounded-full border border-gray-200 shadow-sm">
                                <div>
                                    <div class="text-sm font-bold text-gray-900 truncate w-32" title="{{ $driver->name }}">{{ $driver->name }}</div>
                                    <div class="text-[10px] text-gray-500">Mitra KargoKita</div>
                                </div>
                            </div>
                            <div class="text-sm font-bold text-gray-700">
                                {{ $driver->completed_trips_count }}
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Trending Bids Widget (Static Dummy for visual) -->
            <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-base font-bold text-gray-900 mb-4 flex items-center justify-between pb-2 border-b border-gray-50">
                    <span>Lelang Aktif</span>
                    <span class="text-xs text-brand-blue font-semibold hover:underline cursor-pointer">Lihat Semua</span>
                </h3>
                <div class="space-y-1">
                    <div class="flex items-center justify-between py-2 group cursor-pointer border-b border-gray-50">
                        <div class="flex space-x-3 items-center">
                            <div class="w-10 h-10 bg-orange-50 rounded-xl flex items-center justify-center text-orange-600 group-hover:scale-105 transition transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900 group-hover:text-brand-blue transition truncate w-28">Kayu 5 Ton</div>
                                <div class="text-[10px] text-gray-500">JKT - BDG</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-green-600">Rp 1.5M</div>
                            <div class="text-[10px] text-gray-400">12 penawar</div>
                        </div>
                    </div>
                    
                    <div class="flex items-center justify-between py-2 group cursor-pointer border-b border-gray-50">
                        <div class="flex space-x-3 items-center">
                            <div class="w-10 h-10 bg-gray-50 rounded-xl flex items-center justify-center text-gray-600 group-hover:scale-105 transition transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 002 2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900 group-hover:text-brand-blue transition truncate w-28">Pindahan Kantor</div>
                                <div class="text-[10px] text-gray-500">SBY - MLG</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-green-600">Rp 2.1M</div>
                            <div class="text-[10px] text-gray-400">8 penawar</div>
                        </div>
                    </div>

                    <div class="flex items-center justify-between py-2 group cursor-pointer">
                        <div class="flex space-x-3 items-center">
                            <div class="w-10 h-10 bg-blue-50 rounded-xl flex items-center justify-center text-blue-600 group-hover:scale-105 transition transform">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path></svg>
                            </div>
                            <div>
                                <div class="text-sm font-bold text-gray-900 group-hover:text-brand-blue transition truncate w-28">Besi Beton</div>
                                <div class="text-[10px] text-gray-500">SMG - SLO</div>
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm font-bold text-green-600">Rp 800k</div>
                            <div class="text-[10px] text-gray-400">5 penawar</div>
                        </div>
                    </div>
                </div>
            </div>

            </div> <!-- End sticky wrapper -->
        </div>

    </div>

    <!-- Story Modal -->
    @if($selectedStory)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/90 backdrop-blur-sm" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="relative w-full max-w-md h-full md:h-[80vh] flex flex-col justify-center">
            
            <!-- Close Button -->
            <button wire:click="closeStory" class="absolute -top-12 right-0 md:top-4 md:-right-12 text-white hover:text-gray-300 z-10 focus:outline-none transition transform hover:scale-110">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>

            <!-- Story Content -->
            <div class="relative h-full md:h-auto md:rounded-2xl overflow-hidden bg-gray-900 shadow-2xl">
                <!-- Progress Bar (Dummy) -->
                <div class="absolute top-2 left-2 right-2 flex gap-1 z-10">
                    <div class="h-1 bg-white/30 rounded-full flex-1 overflow-hidden">
                        <div class="h-full bg-white w-3/4"></div>
                    </div>
                </div>

                <!-- User Info -->
                <div class="absolute top-6 left-4 flex items-center z-10">
                    <img src="{{ $selectedStory['avatar'] }}" class="w-10 h-10 rounded-full border-2 border-white shadow-md">
                    <div class="ml-3">
                        <div class="text-white font-bold text-sm drop-shadow-md">{{ $selectedStory['user'] }}</div>
                        <div class="text-white/80 text-xs drop-shadow-md">{{ $selectedStory['time'] }}</div>
                    </div>
                </div>

                <!-- Story Image -->
                <img src="{{ $selectedStory['image'] }}" class="w-full h-full md:max-h-[75vh] object-cover">
                
                <!-- Reply Bar (Dummy) -->
                <form wire:submit.prevent="replyStory" class="absolute bottom-6 left-4 right-4 flex gap-2 z-10">
                    <input type="text" wire:model="replyText" placeholder="Kirim pesan..." class="w-full bg-black/50 backdrop-blur-md border border-white/20 rounded-full text-white placeholder-white/70 px-5 py-3 focus:outline-none focus:border-white focus:ring-0 text-sm shadow-lg transition">
                    <button type="submit" class="text-white p-3 bg-brand-blue rounded-full shadow-lg hover:bg-blue-600 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                        </svg>
                    </button>
                </form>
                
                @if (session()->has('story_message'))
                    <div class="absolute bottom-20 left-4 right-4 bg-green-500/90 backdrop-blur-sm text-white text-xs px-4 py-2 rounded-lg text-center z-20 shadow-lg">
                        {{ session('story_message') }}
                    </div>
                @endif
            </div>
        </div>
    </div>
    @endif
</div>
