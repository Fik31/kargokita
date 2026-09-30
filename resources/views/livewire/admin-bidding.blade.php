<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-6 rounded-lg shadow border-t-4 border-brand-black">
        <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold text-brand-black">Kelola Bidding Muatan</h2>
            <div class="w-64">
                <input type="text" wire:model.live="search" placeholder="Cari merchant..." class="w-full rounded-md border-gray-300 shadow-sm focus:border-brand-blue focus:ring-brand-blue sm:text-sm">
            </div>
        </div>

        @if (session()->has('message'))
            <div class="p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50">
                {{ session('message') }}
            </div>
        @endif

        @if($notifications->count() > 0)
            <div class="mb-6 bg-blue-50 border-l-4 border-brand-blue p-4 rounded-r-lg shadow-sm">
                <div class="flex items-start">
                    <div class="flex-shrink-0">
                        <svg class="h-6 w-6 text-brand-blue" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <h3 class="text-sm font-bold text-brand-blue">Menunggu Pencairan Dana Escrow</h3>
                        <ul class="mt-2 text-sm text-blue-800 list-disc list-inside space-y-1">
                            @foreach($notifications as $notification)
                                <li>{{ $notification->data['message'] }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
        @endif

        <div class="space-y-8">
            @forelse ($loads as $load)
                <div class="border rounded-lg p-6">
                    <div class="flex justify-between items-start mb-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900">Muatan dari: {{ $load->merchant->name }}</h3>
                            <div class="text-sm text-gray-500 mt-2">
                                Tipe: <span class="font-bold">{{ $load->type }}</span> 
                                @if($editingLoadId === $load->id)
                                    <div class="mt-2 bg-gray-50 p-4 rounded-lg border border-gray-200">
                                        <div class="grid grid-cols-2 gap-4">
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700">Total Berat (Ton)</label>
                                                <input type="number" step="0.1" wire:model="total_weight" class="mt-1 block w-full rounded-md border-gray-300 sm:text-sm">
                                                @error('total_weight') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                            <div>
                                                <label class="block text-xs font-bold text-gray-700">Berat Tersisa (Ton)</label>
                                                <input type="number" step="0.1" wire:model="available_weight" class="mt-1 block w-full rounded-md border-gray-300 sm:text-sm">
                                                @error('available_weight') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                        <div class="mt-3 flex gap-2">
                                            <button wire:click="saveLoadWeight" class="bg-blue-600 text-white text-xs px-3 py-1 rounded">Simpan Berat</button>
                                            <button wire:click="cancelEdit" class="bg-gray-400 text-white text-xs px-3 py-1 rounded">Batal</button>
                                        </div>
                                    </div>
                                @else
                                    | Berat: {{ $load->total_weight ?? 'Belum diisi' }} Ton 
                                    @if($load->type === 'LTL') (Sisa: {{ $load->available_weight ?? 'Belum diisi' }} Ton) @endif
                                    <button wire:click="editLoadWeight({{ $load->id }})" class="ml-2 text-xs text-blue-600 underline">Set Berat</button>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <div class="text-sm text-gray-500">Harga Kesepakatan (Bid):</div>
                            @php
                                $acceptedBid = $load->bids->firstWhere('status', 'accepted');
                                $grossAmount = $acceptedBid ? $acceptedBid->amount : $load->max_price;
                                $feePercentage = $load->app_fee_percentage ?? 5;
                                $appFee = $grossAmount * ($feePercentage / 100);
                                $netToDriver = $grossAmount - $appFee;
                            @endphp
                            <div class="font-bold text-gray-800">Rp {{ number_format($grossAmount, 0, ',', '.') }}</div>
                            <div class="text-xs text-red-500 mt-1">App Fee ({{ $feePercentage }}%): -Rp {{ number_format($appFee, 0, ',', '.') }}</div>
                            <div class="text-xs text-green-600 font-bold mt-1 border-t border-gray-200 pt-1">Driver: Rp {{ number_format($netToDriver, 0, ',', '.') }}</div>
                        </div>
                    </div>

                    <h4 class="text-sm font-semibold mb-2 bg-gray-100 p-2 rounded">Status Pembayaran (Escrow)</h4>
                    <div class="flex items-center justify-between bg-gray-50 p-4 rounded-lg border border-gray-200">
                        <div>
                            <span class="text-sm text-gray-500 block">Status Saat Ini:</span>
                            @if($load->escrow_status === 'pending')
                                <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full">Tertahan (Held / Pending)</span>
                            @elseif($load->escrow_status === 'released')
                                <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full">Dana Diteruskan (Released)</span>
                            @else
                                <span class="bg-gray-100 text-gray-800 text-xs font-bold px-2 py-1 rounded-full">{{ ucfirst($load->escrow_status) }}</span>
                            @endif
                        </div>
                        
                        <div class="flex gap-2">
                            <button wire:click="updateEscrowStatus({{ $load->id }}, 'pending')" class="bg-yellow-500 hover:bg-yellow-600 text-white py-1 px-3 rounded text-xs font-bold shadow transition">
                                Tahan Dana (Hold)
                            </button>
                            <button wire:click="updateEscrowStatus({{ $load->id }}, 'released')" class="bg-green-600 hover:bg-green-700 text-white py-1 px-3 rounded text-xs font-bold shadow transition">
                                Teruskan Dana (Release)
                            </button>
                        </div>
                    </div>
                </div>
            @empty
                <div class="text-center text-gray-500 p-6">
                    Tidak ada muatan ditemukan.
                </div>
            @endforelse
            
            <div class="mt-6">
                {{ $loads->links() }}
            </div>
        </div>
    </div>
</div>
