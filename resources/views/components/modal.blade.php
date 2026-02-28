@props([
    'name' => 'modal',
    'title' => '',
    'description' => '',
    'maxWidth' => '2xl', // sm, md, lg, xl, 2xl, 3xl, 4xl
])

@php
$maxWidthClass = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
][$maxWidth];
@endphp

<div 
    x-data="{ show: false }"
    x-on:open-modal-{{ $name }}.window="show = true"
    x-on:close-modal-{{ $name }}.window="show = false"
    x-on:keydown.escape.window="show = false"
    x-show="show"
    class="fixed inset-0 z-50 overflow-y-auto"
    style="display: none;"
>
    <!-- Overlay -->
    <div 
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 bg-gray-900/50 "
        @click="show = false"
    ></div>

    <!-- Modal Container -->
    <div class="flex items-center justify-center min-h-screen px-4 py-6">
        <!-- Modal Content -->
        <div 
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-95"
            x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
            class="relative bg-white rounded-2xl shadow-2xl {{ $maxWidthClass }} w-full"
            @click.stop
        >
            <!-- Close Button -->
            <button 
                @click="show = false"
                class="absolute top-4 right-4 text-gray-400 hover:text-gray-600 transition"
            >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>

            <!-- Header -->
            <div class="px-8 pt-8 pb-6">
                <h2 class="text-2xl font-bold text-gray-900 mb-2">{{ $title }}</h2>
                @if($description)
                    <p class="text-gray-600">{{ $description }}</p>
                @endif
            </div>

            <!-- Body -->
            <div class="px-8 pb-8">
                {{ $slot }}
            </div>
        </div>
    </div>
</div>
