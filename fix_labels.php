<?php
$file = 'c:\\laragon\\www\\kargokita\\resources\\views\\livewire\\driver-cockpit.blade.php';
$content = file_get_contents($file);

// Find each <label class="flex flex-col... and replace with <div @click="$dispatch('open-camera', 'modelName')"...
// And replace </label> with </div> for those blocks.
// And add id="input_modelName" to the hidden file input.

$pattern = '/<label class="flex flex-col items-center justify-center w-full h-32(.*?)"(.*?)>(.*?)<input type="file" accept="image\/\*" capture="environment" wire:model\.live="([a-zA-Z0-9_]+)" class="hidden"(.*?)>\s*<\/label>/ms';

$content = preg_replace_callback($pattern, function($matches) {
    $classRest = $matches[1];
    $attrs = $matches[2];
    $innerHtml = $matches[3];
    $modelName = $matches[4];
    $inputRest = $matches[5];

    $html = '<div @click="$dispatch(\'open-camera\', \'' . $modelName . '\')" class="flex flex-col items-center justify-center w-full h-32' . $classRest . '"' . $attrs . '>';
    $html .= $innerHtml;
    $html .= '<input type="file" id="input_' . $modelName . '" accept="image/*" capture="environment" wire:model.live="' . $modelName . '" class="hidden"' . $inputRest . '>';
    $html .= '</div>';

    return $html;
}, $content);

file_put_contents($file, $content);
echo "Replaced properly\n";
