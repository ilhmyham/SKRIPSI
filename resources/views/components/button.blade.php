@props([
    'variant' => 'primary', // primary, secondary, danger
    'size' => 'default', // default, sm, lg
    'icon' => null,
    'type' => 'button',
    'href' => null,
])

@php
$baseClasses = 'inline-flex items-center justify-center gap-2 font-semibold rounded-lg transition focus:outline-none focus:ring-2 focus:ring-offset-2';

$variantClasses = [
    'primary' => 'bg-emerald-600 text-white hover:bg-emerald-700 focus:ring-emerald-500',
    'secondary' => 'bg-gray-200 text-gray-700 hover:bg-gray-300 focus:ring-gray-400',
    'danger' => 'bg-red-600 text-white hover:bg-red-700 focus:ring-red-500',
];

$sizeClasses = [
    'sm' => 'px-3 py-2 text-sm',
    'default' => 'px-6 py-3 text-base',
    'lg' => 'px-8 py-4 text-lg',
];

$classes = $baseClasses . ' ' . ($variantClasses[$variant] ?? $variantClasses['primary']) . ' ' . ($sizeClasses[$size] ?? $sizeClasses['default']);
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-icon :name="$icon" class="w-5 h-5" />
        @endif
        {{ $slot }}
    </a>
@else
    <button type="{{ $type }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon)
            <x-icon :name="$icon" class="w-5 h-5" />
        @endif
        {{ $slot }}
    </button>
@endif
