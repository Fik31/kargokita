@props(['modelName', 'title', 'requiredStatus', 'activeTrip', 'photoVar'])

<div>
    <label class="block text-xs font-bold text-gray-700 mb-2">{{ $title }}</label>
    @if($photoVar)
        <div class="relative w-full h-32 rounded-xl overflow-hidden border-2 border-brand-blue border-solid group">
            <img src="{{ $photoVar->temporaryUrl() }}" class="w-full h-full object-cover">
            <div class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center opacity-0 group-hover:opacity-100 transition gap-2">
                <button type="button" @click.stop="$dispatch('preview-photo', '{{ $photoVar->temporaryUrl() }}')" class="bg-white text-gray-800 px-4 py-1.5 rounded-full text-xs font-bold shadow-md hover:bg-gray-200">🔍 Lihat Preview</button>
                <button type="button" @click.stop="$dispatch('open-camera', '{{ $modelName }}')" class="bg-brand-blue text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-md hover:bg-blue-600">📷 Ganti Foto</button>
            </div>
            <input type="file" id="input_{{ $modelName }}" accept="image/*" capture="environment" @change="handleFallback($event, '{{ $modelName }}')" class="hidden" {{ $activeTrip->status !== $requiredStatus ? 'disabled' : '' }}>
        </div>
    @else
        <div @click="$dispatch('open-camera', '{{ $modelName }}')" class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-brand-blue rounded-xl bg-blue-50 cursor-pointer hover:bg-blue-100 transition relative overflow-hidden {{ $activeTrip->status !== $requiredStatus ? 'opacity-50 cursor-not-allowed pointer-events-none' : '' }}">
            <svg class="w-8 h-8 text-brand-blue mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
            <span class="text-sm font-bold text-brand-blue">Buka Kamera</span>
            <span class="text-[10px] text-gray-500 mt-1">Ketuk untuk mengambil foto</span>
            <input type="file" id="input_{{ $modelName }}" accept="image/*" capture="environment" @change="handleFallback($event, '{{ $modelName }}')" class="hidden" {{ $activeTrip->status !== $requiredStatus ? 'disabled' : '' }}>
        </div>
    @endif
    <div wire:loading wire:target="{{ $modelName }}" class="text-xs text-brand-blue mt-1 font-bold animate-pulse">Sedang memproses...</div>
    @error($modelName) <span class="text-red-500 text-xs block mt-1">{{ $message }}</span> @enderror
</div>
