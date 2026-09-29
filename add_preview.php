<?php
$file = 'c:\\laragon\\www\\kargokita\\resources\\views\\livewire\\driver-cockpit.blade.php';
$content = file_get_contents($file);

// We want to match the whole block for each input:
// <div @click="$dispatch('open-camera', 'photo_arrival')" class="...">
//     @if($photo_arrival)
//         <img src="{{ $photo_arrival->temporaryUrl() }}" ...>
//         <div ...>Ganti Foto</div>
//     @else
//         ...
//     @endif
//     <input type="file" ...>
// </div>

// To make it robust, we can match:
$pattern = '/<div @click="\$dispatch\(\'open-camera\', \'([a-zA-Z0-9_]+)\'\)" class="([^"]+)">\s*@if\(\$\1\)\s*<img src="\{\{ \$\1->temporaryUrl\(\) \}\}" class="[^"]+">\s*<div class="[^"]+">\s*<span class="[^"]+">Ganti Foto<\/span>\s*<\/div>\s*@else\s*(.*?)\s*@endif\s*(<input type="file" id="input_\1"[^>]+>)\s*<\/div>/ms';

$content = preg_replace_callback($pattern, function($matches) {
    $modelName = $matches[1];
    $classes = $matches[2];
    $elseContent = $matches[3];
    $inputTag = $matches[4];

    $html = '@if($' . $modelName . ')
    <div class="relative w-full h-32 rounded-xl overflow-hidden border-2 border-brand-blue border-solid group">
        <img src="{{ $' . $modelName . '->temporaryUrl() }}" class="w-full h-full object-cover">
        <div class="absolute inset-0 bg-black/60 flex flex-col items-center justify-center opacity-0 hover:opacity-100 transition gap-2">
            <button type="button" @click.stop="$dispatch(\'preview-photo\', \'{{ $' . $modelName . '->temporaryUrl() }}\')" class="bg-white text-gray-800 px-4 py-1.5 rounded-full text-xs font-bold shadow-md hover:bg-gray-200">🔍 Lihat Preview</button>
            <button type="button" @click.stop="$dispatch(\'open-camera\', \'' . $modelName . '\')" class="bg-brand-blue text-white px-4 py-1.5 rounded-full text-xs font-bold shadow-md hover:bg-blue-600">📷 Ganti Foto</button>
        </div>
        ' . $inputTag . '
    </div>
@else
    <div @click="$dispatch(\'open-camera\', \'' . $modelName . '\')" class="' . $classes . '">
        ' . $elseContent . '
        ' . $inputTag . '
    </div>
@endif';

    return $html;
}, $content);

file_put_contents($file, $content);
echo "Replaced properly\n";
