@props([
    'text' => '',      // Tooltip content (string or HTML)
    'position' => 'top', // top | bottom | left | right
    'icon' => true,    // Show ℹ icon or wrap slot content
])

@php
$positionClasses = match($position) {
    'bottom' => 'top-full mt-2 left-1/2 -translate-x-1/2',
    'left'   => 'right-full mr-2 top-1/2 -translate-y-1/2',
    'right'  => 'left-full ml-2 top-1/2 -translate-y-1/2',
    default  => 'bottom-full mb-2 left-1/2 -translate-x-1/2',
};

$arrowClasses = match($position) {
    'bottom' => 'top-[-6px] left-1/2 -translate-x-1/2 border-l-transparent border-r-transparent border-t-0 border-b-gray-800',
    'left'   => 'right-[-6px] top-1/2 -translate-y-1/2 border-t-transparent border-b-transparent border-r-0 border-l-gray-800',
    'right'  => 'left-[-6px] top-1/2 -translate-y-1/2 border-t-transparent border-b-transparent border-l-0 border-r-gray-800',
    default  => 'bottom-[-6px] left-1/2 -translate-x-1/2 border-l-transparent border-r-transparent border-b-0 border-t-gray-800',
};
@endphp

<span
    x-data="{ show: false }"
    class="relative inline-flex items-center"
    @mouseenter="show = true"
    @mouseleave="show = false"
    @focusin="show = true"
    @focusout="show = false"
>
    @if($icon)
        {{-- Default: clickable ℹ icon --}}
        <button type="button"
                class="w-4 h-4 rounded-full bg-gray-200 hover:bg-emerald-100 text-gray-500 hover:text-emerald-700 flex items-center justify-center text-[10px] font-black transition-colors focus:outline-none focus:ring-2 focus:ring-emerald-400"
                aria-label="Info"
                tabindex="0">
            i
        </button>
    @else
        {{-- Wrap arbitrary slot content --}}
        {{ $slot }}
    @endif

    {{-- Tooltip bubble --}}
    <span
        x-show="show"
        x-transition:enter="transition ease-out duration-150"
        x-transition:enter-start="opacity-0 scale-95"
        x-transition:enter-end="opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-100"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="absolute {{ $positionClasses }} z-50 w-64 px-3 py-2 bg-gray-800 text-white text-xs rounded-lg shadow-xl pointer-events-none"
        style="display: none;"
        role="tooltip"
    >
        {!! $text !!}
        {{-- Arrow --}}
        <span class="absolute {{ $arrowClasses }} w-0 h-0 border-4"></span>
    </span>
</span>
