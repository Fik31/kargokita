<div>
    <form wire:submit.prevent="save" class="space-y-4 mb-6">
        @if (session()->has('message'))
            <div class="p-3 bg-green-50 text-green-700 rounded-lg text-sm font-medium border border-green-100">
                {{ session('message') }}
            </div>
        @endif
        @if (session()->has('error'))
            <div class="p-3 bg-red-50 text-red-700 rounded-lg text-sm font-medium border border-red-100">
                {{ session('error') }}
            </div>
        @endif

        <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
                <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Foto</label>
                <div class="flex items-center justify-center w-full">
                    <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-gray-300 border-dashed rounded-xl cursor-pointer bg-gray-50 hover:bg-gray-100 transition-colors">
                        <div class="flex flex-col items-center justify-center pt-5 pb-6">
                            <svg class="w-8 h-8 mb-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                            <p class="mb-1 text-sm text-gray-500"><span class="font-bold">Klik untuk upload</span></p>
                            <p class="text-xs text-gray-500">PNG, JPG, JPEG (Maks. 5MB)</p>
                        </div>
                        <input type="file" wire:model="photo" class="hidden" accept="image/*" />
                    </label>
                </div>
                @error('photo') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
            </div>

            <div class="flex-1 flex flex-col justify-end space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                    <input type="text" wire:model="caption" class="block w-full border-gray-300 rounded-xl shadow-sm focus:ring-brand-blue focus:border-brand-blue sm:text-sm" placeholder="Contoh: Tampak Dalam Box Fuso">
                    @error('caption') <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span> @enderror
                </div>
                
                <button type="submit" class="w-full bg-brand-blue text-white font-bold py-2.5 px-4 rounded-xl hover:bg-blue-700 transition shadow-sm" wire:loading.attr="disabled">
                    <span wire:loading.remove wire:target="save">Unggah ke Galeri</span>
                    <span wire:loading wire:target="save">Mengunggah...</span>
                </button>
            </div>
        </div>
        
        @if ($photo)
            <div class="mt-4 p-4 border border-blue-100 bg-blue-50 rounded-xl flex items-center gap-4">
                <img src="{{ $photo->temporaryUrl() }}" class="h-20 w-20 object-cover rounded-lg border border-white shadow-sm">
                <div class="text-sm text-brand-blue font-medium">Foto siap diunggah. Tambahkan keterangan jika perlu, lalu klik tombol "Unggah".</div>
            </div>
        @endif
    </form>

    <div class="border-t border-gray-100 pt-6">
        <h4 class="text-sm font-bold text-gray-900 mb-4 flex justify-between items-center">
            <span>Foto Galeri Anda</span>
            <span class="bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded font-medium">{{ $galleries->count() }} / 6 Foto</span>
        </h4>
        
        @if($galleries->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                @foreach($galleries as $gallery)
                    <div class="relative group rounded-xl overflow-hidden border border-gray-200 shadow-sm aspect-square bg-gray-50">
                        <img src="{{ Storage::url($gallery->image_path) }}" alt="{{ $gallery->caption }}" class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-black/60 opacity-0 group-hover:opacity-100 transition-opacity flex flex-col justify-between p-3 text-white">
                            <p class="text-xs font-medium truncate">{{ $gallery->caption ?? 'Tanpa Keterangan' }}</p>
                            <button wire:click="delete({{ $gallery->id }})" wire:confirm="Yakin ingin menghapus foto ini?" class="bg-red-500 hover:bg-red-600 text-white rounded p-1.5 self-end transition">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 bg-gray-50 rounded-xl border border-dashed border-gray-200">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                <p class="mt-2 text-sm text-gray-500">Galeri masih kosong. Mulai unggah foto portofolio armada atau gudang Anda!</p>
            </div>
        @endif
    </div>
</div>
