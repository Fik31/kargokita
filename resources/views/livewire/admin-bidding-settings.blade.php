<div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h2 class="text-2xl font-bold text-gray-900">Pengaturan Bidding & Iklan</h2>
            <p class="text-sm text-gray-500">Atur tampilan kotak Top Bid dan kelola pengajuan Iklan dari merchant.</p>
        </div>
    </div>

    @if (session()->has('message'))
        <div class="p-4 mb-6 text-sm text-green-800 rounded-lg bg-green-50 border border-green-200">
            {{ session('message') }}
        </div>
    @endif
    @if (session()->has('error'))
        <div class="p-4 mb-6 text-sm text-blue-800 rounded-lg bg-blue-50 border border-blue-200">
            {{ session('error') }}
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Top Bid Settings -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4">Pengaturan Top Bid</h3>
            
            <form wire:submit.prevent="saveSettings">
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Mode Penentuan Top Bid (3 Kotak)</label>
                    <div class="flex space-x-6">
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="topBidMode" value="auto" class="text-brand-blue focus:ring-brand-blue h-4 w-4">
                            <span class="ml-2 text-sm text-gray-700">Otomatis (Berdasarkan Performa)</span>
                        </label>
                        <label class="flex items-center">
                            <input type="radio" wire:model.live="topBidMode" value="manual" class="text-brand-blue focus:ring-brand-blue h-4 w-4">
                            <span class="ml-2 text-sm text-gray-700">Manual (Pilih Sendiri)</span>
                        </label>
                    </div>
                </div>

                @if($topBidMode === 'manual')
                    <div class="mb-6 bg-gray-50 p-4 rounded-xl border border-gray-200">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pilih Merchant (Maksimal 3)</label>
                        <p class="text-xs text-gray-500 mb-3">Pilih merchant yang akan ditampilkan datanya (dummy info) di kotak Top Bid.</p>
                        
                        <div class="space-y-2 max-h-60 overflow-y-auto pr-2">
                            @foreach($availableMerchants as $merchant)
                                <label class="flex items-center p-2 hover:bg-white rounded border border-transparent hover:border-gray-200 transition">
                                    <input type="checkbox" wire:model="selectedMerchants" value="{{ $merchant->id }}" class="text-brand-blue focus:ring-brand-blue rounded h-4 w-4">
                                    <span class="ml-3 text-sm font-medium text-gray-900">{{ $merchant->name }}</span>
                                    <span class="ml-auto text-xs text-gray-500">{{ $merchant->email }}</span>
                                </label>
                            @endforeach
                        </div>
                        <div class="mt-2 text-sm text-gray-600">
                            Terpilih: <span class="font-bold text-brand-blue">{{ count($selectedMerchants) }}</span> / 3
                        </div>
                    </div>
                @endif

                <div>
                    <button type="submit" class="bg-brand-blue hover:bg-blue-700 text-white font-bold py-2 px-6 rounded-lg transition shadow-sm">
                        Simpan Pengaturan
                    </button>
                </div>
            </form>
        </div>

        <!-- Ad Applications Management -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <h3 class="text-lg font-bold text-gray-800 border-b pb-3 mb-4 flex justify-between items-center">
                <span>Pengajuan Iklan Merchant</span>
                <span class="bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">{{ count($adApplications) }} Total</span>
            </h3>
            
            <div class="space-y-4 max-h-[500px] overflow-y-auto pr-2">
                @forelse($adApplications as $app)
                    <div class="border {{ $app->status === 'pending' ? 'border-yellow-300 bg-yellow-50' : 'border-gray-200 bg-white' }} rounded-xl p-4">
                        <div class="flex justify-between items-start mb-2">
                            <div>
                                <h4 class="font-bold text-gray-900">{{ $app->merchant->name }}</h4>
                                <p class="text-xs text-gray-500">Diajukan: {{ $app->created_at->format('d M Y H:i') }}</p>
                            </div>
                            <div>
                                @if($app->status === 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-bold px-2 py-1 rounded-full uppercase">Menunggu</span>
                                @elseif($app->status === 'approved')
                                    <span class="bg-green-100 text-green-800 text-xs font-bold px-2 py-1 rounded-full uppercase">Disetujui</span>
                                @else
                                    <span class="bg-blue-100 text-blue-800 text-xs font-bold px-2 py-1 rounded-full uppercase">Ditolak</span>
                                @endif
                            </div>
                        </div>
                        
                        @if($app->status === 'rejected' && $app->admin_notes)
                            <div class="mt-2 text-xs bg-blue-50 text-blue-700 p-2 rounded">
                                <strong>Alasan Penolakan:</strong> {{ $app->admin_notes }}
                            </div>
                        @endif

                        @if($app->status === 'pending')
                            <div class="mt-4 flex gap-2">
                                @if($rejectingAppId === $app->id)
                                    <div class="w-full bg-white p-3 rounded border border-blue-200">
                                        <input type="text" wire:model="rejectReason" placeholder="Masukkan alasan penolakan..." class="w-full text-sm border-gray-300 rounded mb-2">
                                        @error('rejectReason') <span class="text-red-500 text-xs block mb-2">{{ $message }}</span> @enderror
                                        <div class="flex justify-end gap-2">
                                            <button wire:click="cancelReject" class="text-xs text-gray-500 hover:text-gray-700 px-2 py-1">Batal</button>
                                            <button wire:click="rejectAd" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1 px-3 rounded">Konfirmasi Tolak</button>
                                        </div>
                                    </div>
                                @else
                                    <button wire:click="approveAd({{ $app->id }})" class="bg-green-600 hover:bg-green-700 text-white text-xs font-bold py-1.5 px-3 rounded shadow-sm">Setujui Iklan</button>
                                    <button wire:click="confirmRejectAd({{ $app->id }})" class="bg-gray-100 hover:bg-gray-200 text-blue-600 border border-gray-200 text-xs font-bold py-1.5 px-3 rounded shadow-sm">Tolak</button>
                                @endif
                            </div>
                        @endif
                        
                        @if($app->status === 'approved')
                             <div class="mt-2 pt-2 border-t border-gray-100 text-right">
                                 <button wire:click="confirmRejectAd({{ $app->id }})" class="text-xs text-blue-600 hover:underline">Hentikan Iklan</button>
                             </div>
                             @if($rejectingAppId === $app->id)
                                <div class="mt-2 w-full bg-white p-3 rounded border border-blue-200 text-left">
                                    <input type="text" wire:model="rejectReason" placeholder="Masukkan alasan penghentian..." class="w-full text-sm border-gray-300 rounded mb-2">
                                    @error('rejectReason') <span class="text-red-500 text-xs block mb-2">{{ $message }}</span> @enderror
                                    <div class="flex justify-end gap-2">
                                        <button wire:click="cancelReject" class="text-xs text-gray-500 hover:text-gray-700 px-2 py-1">Batal</button>
                                        <button wire:click="rejectAd" class="bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1 px-3 rounded">Konfirmasi Berhenti</button>
                                    </div>
                                </div>
                            @endif
                        @endif
                    </div>
                @empty
                    <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                        Belum ada pengajuan iklan dari merchant.
                    </div>
                @endforelse
            </div>
        </div>

    </div>
</div>
