<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'name' => 'modal',
    'title' => '',
    'description' => '',
    'maxWidth' => '2xl', // sm, md, lg, xl, 2xl, 3xl, 4xl
]));

foreach ($attributes->all() as $__key => $__value) {
    if (in_array($__key, $__propNames)) {
        $$__key = $$__key ?? $__value;
    } else {
        $__newAttributes[$__key] = $__value;
    }
}

$attributes = new \Illuminate\View\ComponentAttributeBag($__newAttributes);

unset($__propNames);
unset($__newAttributes);

foreach (array_filter(([
    'name' => 'modal',
    'title' => '',
    'description' => '',
    'maxWidth' => '2xl', // sm, md, lg, xl, 2xl, 3xl, 4xl
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
$maxWidthClass = [
    'sm' => 'max-w-sm',
    'md' => 'max-w-md',
    'lg' => 'max-w-lg',
    'xl' => 'max-w-xl',
    '2xl' => 'max-w-2xl',
    '3xl' => 'max-w-3xl',
    '4xl' => 'max-w-4xl',
][$maxWidth];
?>

<div 
    x-data="{ show: false }"
    x-on:open-modal-<?php echo e($name); ?>.window="show = true"
    x-on:close-modal-<?php echo e($name); ?>.window="show = false"
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
            class="relative bg-white rounded-2xl shadow-2xl <?php echo e($maxWidthClass); ?> w-full"
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
                <h2 class="text-2xl font-bold text-gray-900 mb-2"><?php echo e($title); ?></h2>
                <?php if($description): ?>
                    <p class="text-gray-600"><?php echo e($description); ?></p>
                <?php endif; ?>
            </div>

            <!-- Body -->
            <div class="px-8 pb-8">
                <?php echo e($slot); ?>

            </div>
        </div>
    </div>
</div>
<?php /**PATH D:\Ums\semester7\Skripsi\aplikasi\Ayatisyarat6\app6\resources\views/components/modal.blade.php ENDPATH**/ ?>