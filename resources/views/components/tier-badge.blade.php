@props(['tier'])

@php
    $tier = strtolower($tier ?? 'common');
    $colors = [
        'gold' => 'bg-yellow-400 text-yellow-900 border-yellow-500 shadow-yellow-200',
        'silver' => 'bg-gray-300 text-gray-800 border-gray-400 shadow-gray-200',
        'bronze' => 'bg-orange-300 text-orange-900 border-orange-400 shadow-orange-200',
        'trusted' => 'bg-blue-500 text-white border-blue-600 shadow-blue-300',
        'verified' => 'bg-green-100 text-green-800 border-green-300 shadow-none',
        'basic' => 'bg-gray-100 text-gray-600 border-gray-200 shadow-none',
        'not_eligible' => 'bg-red-100 text-red-800 border-red-200 shadow-none',
        'common' => 'bg-gray-100 text-gray-600 border-gray-200 shadow-none',
    ];

    $icons = [
        'gold' => '👑',
        'silver' => '🥈',
        'bronze' => '🥉',
        'trusted' => '🛡️',
        'verified' => '✅',
        'basic' => '🏢',
        'not_eligible' => '❌',
        'common' => '👤',
    ];

    $colorClass = $colors[$tier] ?? $colors['common'];
    $icon = $icons[$tier] ?? $icons['common'];
    $tierName = ucfirst($tier);
@endphp

<span {{ $attributes->merge(['class' => "inline-flex items-center px-2 py-0.5 rounded text-xs font-bold border shadow-sm $colorClass"]) }}>
    <span class="mr-1">{{ $icon }}</span> {{ $tierName }}
</span>
