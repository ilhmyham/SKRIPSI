@props([
    'title',
    'value',
    'icon' => 'chart',
    'color' => 'emerald',
])

@php
$colorClasses = [
    'emerald' => 'bg-emerald-100 text-emerald-600',
    'blue' => 'bg-blue-100 text-blue-600',
    'purple' => 'bg-purple-100 text-purple-600',
    'yellow' => 'bg-yellow-100 text-yellow-600',
];

$iconBg = $colorClasses[$color] ?? $colorClasses['emerald'];
@endphp

<x-card {{ $attributes }}>
    <div class="flex items-center justify-between">
        <div>
            <p class="text-sm font-medium" style="color: var(--color-text-secondary);">{{ $title }}</p>
            <p class="text-3xl font-bold mt-2" style="color: var(--color-primary);">{{ $value }}</p>
        </div>
        <div class="p-4 {{ $iconBg }} rounded-full">
            <x-icon :name="$icon" class="w-8 h-8" />
        </div>
    </div>
</x-card>
