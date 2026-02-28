@props([
    'title' => null,
    'interactive' => false,
])

@php
$classes = 'bg-white rounded-xl p-6 shadow-sm border border-gray-100 transition';
if ($interactive) {
    $classes .= ' hover:shadow-md hover:border-emerald-200 cursor-pointer';
}
@endphp

<div {{ $attributes->merge(['class' => $classes]) }}>
    @if($title)
        <h3 class="text-xl font-bold mb-4" style="color: var(--color-primary);">{{ $title }}</h3>
    @endif
    
    {{ $slot }}
</div>
