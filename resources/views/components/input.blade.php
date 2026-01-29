@props([
    'label',
    'name',
    'type' => 'text',
    'required' => false,
    'placeholder' => '',
])

<div {{ $attributes->only('class') }}>
    @if($label)
        <label for="{{ $name }}" class="block text-lg font-semibold mb-2">
            {{ $label }}
            @if($required) <span class="text-red-500">*</span> @endif
        </label>
    @endif
    
    <input 
        type="{{ $type }}"
        name="{{ $name }}"
        id="{{ $name }}"
        @if($required) required @endif
        @if($placeholder) placeholder="{{ $placeholder }}" @endif
        {{ $attributes->except('class')->merge(['class' => 'w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:border-emerald-100 focus:ring focus:ring-emerald-200 transition']) }}
    >
    
    @error($name)
        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
    @enderror
</div>
