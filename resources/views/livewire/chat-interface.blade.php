<div class="max-w-6xl mx-auto h-[80vh] flex bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
    <!-- Sidebar -->
    <div class="w-1/3 border-r border-gray-200 bg-gray-50 flex flex-col">
        <div class="p-4 border-b border-gray-200 bg-white">
            <h2 class="text-xl font-bold text-gray-800">Pesan</h2>
            <div class="mt-4 relative">
                <input type="text" placeholder="Cari kontak..." class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg text-sm focus:ring-brand-blue focus:border-brand-blue">
                <div class="absolute left-3 top-2.5 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
        
        <div class="flex-1 overflow-y-auto">
            @foreach($contacts as $contact)
            <div wire:click="selectContact('{{ $contact['id'] }}')" class="flex items-center p-4 border-b border-gray-100 cursor-pointer transition {{ $selectedContactId == $contact['id'] ? 'bg-blue-50' : 'hover:bg-gray-100' }}">
                <div class="relative">
                    <img src="{{ $contact['avatar'] }}" alt="{{ $contact['name'] }}" class="w-12 h-12 rounded-full border border-gray-200">
                    @if($contact['unread'] > 0)
                        <span class="absolute top-0 right-0 bg-brand-blue text-white text-xs font-bold rounded-full w-5 h-5 flex items-center justify-center border-2 border-white">{{ $contact['unread'] }}</span>
                    @endif
                </div>
                <div class="ml-4 flex-1">
                    <div class="flex justify-between items-baseline">
                        <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $contact['name'] }}</h3>
                        <span class="text-xs text-gray-500 whitespace-nowrap">{{ $contact['time'] }}</span>
                    </div>
                    <div class="flex items-center gap-1 mt-1">
                        <span class="text-[10px] bg-gray-200 text-gray-600 px-1.5 rounded">{{ $contact['role'] }}</span>
                        <p class="text-sm text-gray-600 truncate">{{ $contact['lastMessage'] }}</p>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>

    <!-- Main Chat Area -->
    <div class="w-2/3 flex flex-col bg-slate-50">
        @if($selectedContactId)
            @php 
                $contact = collect($contacts)->firstWhere('id', $selectedContactId);
            @endphp
            <!-- Chat Header -->
            <div class="bg-yellow-50 text-yellow-800 text-xs px-4 py-2 border-b border-yellow-200 flex items-center justify-center font-medium">
                <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Chat hanya untuk laporan pengiriman. Hindari transaksi di luar aplikasi.
            </div>
            <div class="p-4 border-b border-gray-200 bg-white flex items-center justify-between">
                <div class="flex items-center">
                    <img src="{{ $contact['avatar'] }}" alt="{{ $contact['name'] }}" class="w-10 h-10 rounded-full border border-gray-200">
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-gray-900">{{ $contact['name'] }}</h3>
                        <p class="text-xs text-gray-500">Online</p>
                    </div>
                </div>
                <div>
                    @if(isset($contact['is_admin_view']) && $contact['is_admin_view'])
                        @if($contact['admin_assistance_requested'])
                            <span class="px-3 py-1 bg-red-100 text-red-600 rounded-full text-xs font-bold">Bantuan Diminta</span>
                        @endif
                    @elseif(isset($contact['is_read_only']) && !$contact['is_read_only'])
                        @if(isset($contact['admin_assistance_requested']) && $contact['admin_assistance_requested'])
                            <span class="text-xs font-bold text-red-500 px-3 py-1 bg-red-50 rounded-full border border-red-100">Bantuan Admin Aktif</span>
                        @else
                            <button wire:click="requestAdminAssistance" class="text-xs font-bold text-white bg-red-500 hover:bg-red-600 px-3 py-1.5 rounded-full transition shadow-sm">
                                Minta Bantuan Admin
                            </button>
                        @endif
                    @endif
                </div>
            </div>

            <!-- Chat Messages -->
            <div class="flex-1 overflow-y-auto p-4 space-y-4">
                @forelse($activeMessages as $message)
                    @if($message['sender'] == 'admin')
                        @if(isset($contact['is_admin_view']) && $contact['is_admin_view'])
                            <div class="flex items-end gap-2 justify-end">
                                <div class="max-w-[70%] text-right">
                                    <div class="bg-red-600 p-3 rounded-2xl rounded-br-none shadow-sm text-white text-sm text-left inline-block">
                                        {{ $message['text'] }}
                                    </div>
                                    <span class="text-xs text-gray-400 mt-1 block">{{ $message['time'] }}</span>
                                </div>
                            </div>
                        @else
                            <div class="flex items-end gap-2">
                                <div class="w-8 h-8 rounded-full border border-gray-200 flex-shrink-0 bg-red-100 flex items-center justify-center text-red-600 text-xs font-bold">ADM</div>
                                <div class="max-w-[70%]">
                                    <span class="text-xs font-bold text-red-600 mb-1 block">Administrator</span>
                                    <div class="bg-red-50 p-3 rounded-2xl rounded-bl-none shadow-sm border border-red-200 text-red-900 text-sm">
                                        {{ $message['text'] }}
                                    </div>
                                    <span class="text-xs text-gray-400 mt-1 block">{{ $message['time'] }}</span>
                                </div>
                            </div>
                        @endif
                    @elseif($message['sender'] == 'them' || $message['sender'] == 'driver' || $message['sender'] == 'merchant')
                        <div class="flex items-end gap-2">
                            @if(!isset($contact['is_admin_view']) || !$contact['is_admin_view'])
                                <img src="{{ $contact['avatar'] }}" class="w-8 h-8 rounded-full border border-gray-200 flex-shrink-0">
                            @endif
                            <div class="max-w-[70%]">
                                @if(isset($contact['is_admin_view']) && $contact['is_admin_view'])
                                    <span class="text-xs font-bold text-gray-500 mb-1 block">{{ $message['sender'] == 'driver' ? 'Driver' : 'Merchant' }}</span>
                                @endif
                                <div class="bg-white p-3 rounded-2xl rounded-bl-none shadow-sm border border-gray-100 text-gray-800 text-sm">
                                    {{ $message['text'] }}
                                </div>
                                <span class="text-xs text-gray-400 mt-1 block">{{ $message['time'] }}</span>
                            </div>
                        </div>
                    @else
                        <div class="flex items-end gap-2 justify-end">
                            <div class="max-w-[70%] text-right">
                                <div class="bg-brand-blue p-3 rounded-2xl rounded-br-none shadow-sm text-white text-sm text-left inline-block">
                                    {{ $message['text'] }}
                                </div>
                                <span class="text-xs text-gray-400 mt-1 block">{{ $message['time'] }}</span>
                            </div>
                        </div>
                    @endif
                @empty
                    <div class="h-full flex items-center justify-center text-gray-400 text-sm">Belum ada percakapan.</div>
                @endforelse
            </div>

            <!-- Chat Input -->
            @if(isset($contact['is_admin_view']) && $contact['is_admin_view'])
                @if(!$contact['admin_assistance_requested'])
                    <div class="p-4 bg-gray-100 border-t border-gray-200 text-center text-gray-500 text-sm font-medium">
                        Anda sedang memantau chat ini (Mode Administrator). Chat terkunci.
                    </div>
                @else
                    <div class="p-4 bg-red-50 border-t border-red-200">
                        <form wire:submit.prevent="sendMessage" class="flex gap-2">
                            <input type="text" wire:model="newMessage" placeholder="Ketik pesan bantuan..." class="flex-1 bg-white border border-red-300 rounded-full px-4 py-2 text-sm focus:ring-red-500 focus:border-red-500">
                            <button type="submit" class="bg-red-500 text-white p-2 rounded-full hover:bg-red-600 transition flex items-center justify-center w-10 h-10 shadow-sm">
                                <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                            </button>
                        </form>
                    </div>
                @endif
            @elseif($contact['is_read_only'])
                <div class="p-4 bg-gray-100 border-t border-gray-200 text-center text-gray-500 text-sm font-medium">
                    Sesi percakapan ini telah ditutup karena transaksi telah selesai.
                </div>
            @else
                <div class="p-4 bg-white border-t border-gray-200">
                    <form wire:submit.prevent="sendMessage" class="flex gap-2">
                        <button type="button" class="text-gray-400 hover:text-gray-600 p-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path></svg>
                        </button>
                        <input type="text" wire:model="newMessage" placeholder="Ketik pesan..." class="flex-1 bg-gray-50 border border-gray-300 rounded-full px-4 py-2 text-sm focus:ring-brand-blue focus:border-brand-blue">
                        <button type="submit" class="bg-brand-blue text-white p-2 rounded-full hover:bg-blue-700 transition flex items-center justify-center w-10 h-10 shadow-sm">
                            <svg class="w-5 h-5 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                        </button>
                    </form>
                </div>
            @endif
        @else
            <!-- Placeholder -->
            <div class="flex-1 flex flex-col items-center justify-center text-gray-400 p-6 text-center">
                <svg class="w-20 h-20 mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path>
                </svg>
                <p class="text-lg font-medium text-gray-500">Pilih pesan untuk mulai mengobrol</p>
                <p class="text-sm mt-1 mb-4">Chat langsung dengan Driver atau Merchant terkait muatan Anda.</p>
                <div class="bg-yellow-50 text-yellow-800 p-4 rounded-lg text-sm max-w-md border border-yellow-200 text-left">
                    <span class="font-bold flex items-center mb-2">
                        <svg class="w-4 h-4 mr-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Aturan Chat Kargokita
                    </span>
                    <ul class="list-disc pl-5 space-y-1">
                        <li>Chat hanya terbuka bagi pihak yang memiliki transaksi aktif atau yang baru selesai.</li>
                        <li>Batas waktu chat adalah <b>1x24 jam</b> setelah barang sampai tujuan.</li>
                        <li>Dilarang melakukan transaksi atau negosiasi di luar aplikasi (gunakan fitur <b>Bid</b>).</li>
                    </ul>
                </div>
            </div>
        @endif
    </div>
</div>
