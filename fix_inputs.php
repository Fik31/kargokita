<?php

$file = 'c:\\laragon\\www\\kargokita\\resources\\views\\livewire\\driver-cockpit.blade.php';
$content = file_get_contents($file);

$pattern = '/<input type="file" id="input_([a-zA-Z0-9_]+)" accept="image\/\*" capture="environment" wire:model\.live="([a-zA-Z0-9_]+)" class="hidden"(.*?)>/ms';

$content = preg_replace_callback($pattern, function ($matches) {
    $modelName = $matches[1]; // e.g. photo_arrival
    $rest = $matches[3];

    // Remove wire:model.live and add @change
    return '<input type="file" id="input_'.$modelName.'" accept="image/*" capture="environment" @change="handleFallback($event, \''.$modelName.'\')" class="hidden"'.$rest.'>';
}, $content);

file_put_contents($file, $content);
echo "Replaced properly\n";
