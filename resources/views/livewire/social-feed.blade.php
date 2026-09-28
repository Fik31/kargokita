<div class="max-w-3xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    
    <div class="max-w-3xl mx-auto space-y-6">

        <!-- Stories Section -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
            <div class="flex space-x-4 overflow-x-auto pb-2 scrollbar-hide">
                <!-- Add Story Button (Dummy) -->
                <div class="flex flex-col items-center flex-shrink-0 cursor-pointer">
                    <div class="w-16 h-16 rounded-full p-0.5 bg-gray-200 flex items-center justify-center relative">
                        <img src="https://ui-avatars.com/api/?name=You&background=fff" class="w-full h-full rounded-full border-2 border-white object-cover">
                        <div class="absolute bottom-0 right-0 bg-brand-blue text-white rounded-full w-5 h-5 flex items-center justify-center border-2 border-white text-xs font-bold">+</div>
                    </div>
                    <span class="text-xs text-gray-600 mt-1 font-medium">Buat Story</span>
                </div>

                <!-- Story Items -->
                @foreach($stories as $story)
                <div class="flex flex-col items-center flex-shrink-0 cursor-pointer" wire:click="viewStory({{ $story['id'] }})">
                    <div class="w-16 h-16 rounded-full p-0.5 bg-gradient-to-tr from-yellow-400 to-brand-blue flex items-center justify-center">
                        <img src="{{ $story['avatar'] }}" class="w-full h-full rounded-full border-2 border-white object-cover">
                    </div>
                    <span class="text-xs text-gray-600 mt-1 font-medium truncate w-16 text-center">{{ strtok($story['user'], ' ') }}</span>
                </div>
                @endforeach
            </div>
        </div>

        <!-- Driver Ranking -->
        @if($topDrivers->isNotEmpty())
        <div class="bg-gradient-to-r from-blue-50 to-white p-6 rounded-xl shadow-sm border border-blue-100">
            <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center">
                <svg class="w-5 h-5 text-yellow-500 mr-2" fill="currentColor" viewBox="0 0 20 20"><path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path></svg>
                Driver Teraktif
            </h3>
            <div class="flex space-x-4 overflow-x-auto pb-2 scrollbar-hide">
                @foreach($topDrivers as $index => $driver)
                    <div class="flex flex-col items-center flex-shrink-0 bg-white p-3 rounded-lg shadow-sm border border-gray-100 w-28 text-center relative">
                        <div class="absolute -top-2 -right-2 bg-yellow-400 text-brand-black text-xs font-bold w-6 h-6 rounded-full flex items-center justify-center border-2 border-white shadow-sm">
                            #{{ $index + 1 }}
                        </div>
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($driver->name) }}&background=random" class="w-12 h-12 rounded-full mb-2 border border-gray-200">
                        <span class="text-xs font-bold text-gray-900 truncate w-full" title="{{ $driver->name }}">{{ $driver->name }}</span>
                        <span class="text-[10px] text-gray-500 mt-1">{{ $driver->completed_trips_count }} Trip</span>
                    </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Form Create Post -->
        <div class="bg-white p-6 rounded-lg shadow mb-8 border-l-4 border-brand-blue">
            <h3 class="text-lg font-bold text-gray-800 mb-4">Buat Status atau Info</h3>
            @if (session()->has('message'))
                <div class="p-3 mb-4 text-sm text-green-800 rounded bg-green-50">
                    {{ session('message') }}
                </div>
            @endif
            @if (session()->has('error'))
                <div class="p-3 mb-4 text-sm text-blue-800 rounded bg-blue-50">
                    {{ session('error') }}
                </div>
            @endif
            <form wire:submit.prevent="createPost">
                <div class="mb-4">
                    <textarea wire:model="content" rows="3" class="w-full border-gray-300 rounded-md shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue focus:ring-opacity-50" placeholder="Apa yang ingin Anda bagikan atau cari?"></textarea>
                    @error('content') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Unggah Foto (Opsional)</label>
                    <input type="file" wire:model="image" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-brand-blue hover:file:bg-blue-100">
                    @error('image') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                </div>
                <div class="flex items-center justify-between">
                    <select wire:model="type" class="border-gray-300 rounded-md shadow-sm focus:border-brand-blue focus:ring focus:ring-brand-blue focus:ring-opacity-50 text-sm">
                        <option value="status">Status Umum</option>
                        <option value="seeking_load">Cari Muatan (Driver)</option>
                        <option value="seeking_driver">Cari Driver (Merchant)</option>
                    </select>
                    <button type="submit" class="bg-brand-blue hover:bg-blue-700 text-white font-bold py-2 px-6 rounded shadow">
                        Bagikan
                    </button>
                </div>
            </form>
        </div>

        <!-- Story Modal -->
        @if($selectedStory)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black bg-opacity-95" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="relative w-full max-w-md h-full md:h-[80vh] flex flex-col justify-center">
                
                <!-- Close Button -->
                <button wire:click="closeStory" class="absolute top-4 right-4 text-white hover:text-gray-300 z-10 focus:outline-none">
                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>

                <!-- Story Content -->
                <div class="relative h-full md:h-auto rounded-lg overflow-hidden bg-gray-900 shadow-2xl">
                    <!-- Progress Bar (Dummy) -->
                    <div class="absolute top-2 left-2 right-2 flex gap-1 z-10">
                        <div class="h-1 bg-white bg-opacity-30 rounded-full flex-1 overflow-hidden">
                            <div class="h-full bg-white w-3/4"></div>
                        </div>
                    </div>

                    <!-- User Info -->
                    <div class="absolute top-6 left-4 flex items-center z-10">
                        <img src="{{ $selectedStory['avatar'] }}" class="w-8 h-8 rounded-full border border-white">
                        <div class="ml-2">
                            <span class="text-white font-semibold text-sm">{{ $selectedStory['user'] }}</span>
                            <span class="text-gray-300 text-xs ml-2">{{ $selectedStory['time'] }}</span>
                        </div>
                    </div>

                    <!-- Story Image -->
                    <img src="{{ $selectedStory['image'] }}" class="w-full h-full md:max-h-[70vh] object-cover">
                    
                    <!-- Reply Bar (Dummy) -->
                    <form wire:submit.prevent="replyStory" class="absolute bottom-4 left-4 right-4 flex gap-2 z-10">
                        <input type="text" wire:model="replyText" placeholder="Balas story..." class="w-full bg-black bg-opacity-40 border border-gray-500 rounded-full text-white placeholder-gray-300 px-4 py-2 focus:outline-none focus:border-white focus:ring-0 text-sm">
                        <button type="submit" class="text-white p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </button>
                    </form>
                    
                    @if (session()->has('story_message'))
                        <div class="absolute bottom-16 left-4 right-4 bg-green-500 text-white text-xs px-3 py-2 rounded-lg text-center z-20">
                            {{ session('story_message') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>

    <!-- Feed List -->
    <div class="space-y-6">
        @forelse ($posts as $post)
            <div class="bg-white p-6 rounded-lg shadow">
                <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-3">
                        <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center text-gray-600 font-bold">
                            {{ substr($post->user->name, 0, 1) }}
                        </div>
                        <div>
                            <div class="font-bold text-brand-black">{{ $post->user->name }}</div>
                            <div class="text-xs text-gray-500">{{ $post->created_at->diffForHumans() }}</div>
                        </div>
                    </div>
                    <span class="px-3 py-1 text-xs rounded-full {{ $post->type === 'status' ? 'bg-gray-100 text-gray-800' : 'bg-blue-100 text-brand-blue font-bold' }}">
                        {{ ucwords(str_replace('_', ' ', $post->type)) }}
                    </span>
                </div>
                <div class="text-gray-800 whitespace-pre-wrap">{{ $post->content }}</div>
                @if($post->image)
                    <div class="mt-4">
                        <img src="{{ Storage::url($post->image) }}" alt="Post image" class="rounded-lg max-h-96 object-cover">
                    </div>
                @endif
                <div class="mt-4 pt-4 border-t border-gray-100 flex items-center">
                    <button wire:click="likePost({{ $post->id }})" class="flex items-center text-gray-500 hover:text-brand-blue transition">
                        <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.514"></path></svg>
                        <span class="text-sm font-medium">Like</span>
                    </button>
                    @if(!Auth::user()->is_subscribed)
                        <span class="ml-2 text-[10px] bg-yellow-100 text-yellow-800 px-2 py-0.5 rounded border border-yellow-200">Fitur VIP</span>
                    @endif
                </div>
            </div>
        @empty
            <div class="text-center text-gray-500 bg-white p-6 rounded-lg shadow">
                Belum ada postingan di beranda.
            </div>
        @endforelse
    </div>
</div>
