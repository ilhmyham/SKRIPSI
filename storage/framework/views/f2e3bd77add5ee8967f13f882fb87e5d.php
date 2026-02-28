<?php $attributes ??= new \Illuminate\View\ComponentAttributeBag;

$__newAttributes = [];
$__propNames = \Illuminate\View\ComponentAttributeBag::extractPropNames(([
    'items' => [],
    'columns' => [],
    'filterKey' => null,
    'filterOptions' => [],
    'searchKeys' => [],
    'itemsPerPage' => 5,
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
    'items' => [],
    'columns' => [],
    'filterKey' => null,
    'filterOptions' => [],
    'searchKeys' => [],
    'itemsPerPage' => 5,
]), 'is_string', ARRAY_FILTER_USE_KEY) as $__key => $__value) {
    $$__key = $$__key ?? $__value;
}

$__defined_vars = get_defined_vars();

foreach ($attributes->all() as $__key => $__value) {
    if (array_key_exists($__key, $__defined_vars)) unset($$__key);
}

unset($__defined_vars, $__key, $__value); ?>

<?php
    $itemsArray = is_array($items) ? $items : $items->toArray();
?>

<div
    x-data="{
        allItems: <?php echo \Illuminate\Support\Js::from($itemsArray)->toHtml() ?>,
        filterValue: 'all',
        search: '',
        currentPage: 1,
        perPage: <?php echo \Illuminate\Support\Js::from($itemsPerPage)->toHtml() ?>,
        perPageOptions: [5, 10, 20, 'all'],
        
        get filteredItems() {
            return this.allItems.filter(item => {
                const matchFilter =
                    !<?php echo \Illuminate\Support\Js::from($filterKey)->toHtml() ?> ||
                    this.filterValue === 'all' ||
                    item[<?php echo \Illuminate\Support\Js::from($filterKey)->toHtml() ?>] === this.filterValue

                const matchSearch =
                    this.search === '' ||
                    <?php echo \Illuminate\Support\Js::from($searchKeys)->toHtml() ?>.some(key => {
                        return String(item[key] ?? '')
                            .toLowerCase()
                            .includes(this.search.toLowerCase())
                    })

                return matchFilter && matchSearch
            })
        },
        
        get paginatedItems() {
            const items = this.filteredItems;
            if (this.perPage === 'all') {
                return items;
            }
            const start = (this.currentPage - 1) * parseInt(this.perPage);
            const end = start + parseInt(this.perPage);
            return items.slice(start, end);
        },
        
        get totalPages() {
            if (this.perPage === 'all') return 1;
            return Math.ceil(this.filteredItems.length / parseInt(this.perPage));
        },
        
        get startIndex() {
            if (this.perPage === 'all') return 1;
            return (this.currentPage - 1) * parseInt(this.perPage) + 1;
        },
        
        get endIndex() {
            if (this.perPage === 'all') return this.filteredItems.length;
            const end = this.currentPage * parseInt(this.perPage);
            return Math.min(end, this.filteredItems.length);
        },
        
        changePage(page) {
            if (page >= 1 && page <= this.totalPages) {
                this.currentPage = page;
            }
        },
        
        changePerPage(value) {
            this.perPage = value;
            this.currentPage = 1;
        },
        
        showPageButton(page) {
            if (this.totalPages <= 7) return true;
            if (page === 1 || page === this.totalPages) return true;
            if (Math.abs(page - this.currentPage) <= 2) return true;
            return false;
        }
    }"
    x-init="$watch('search', () => { currentPage = 1 }); $watch('filterValue', () => { currentPage = 1 })"
    class="rounded-2xl border border-gray-200 bg-white shadow-sm"
>

    
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">

        
        <div class="flex items-center gap-3 flex-1">

            
            <?php if($filterKey && count($filterOptions) > 0): ?>
                <div class="relative" x-data="{ open: false }">
                    <button
                        @click="open = !open"
                        class="inline-flex items-center gap-2 h-9 rounded-md
                               border border-gray-300 bg-white px-3
                               text-sm font-medium text-gray-700
                               hover:bg-gray-50
                               focus:outline-none focus:ring-2 focus:ring-emerald-500/20
                               transition"
                    >
                        <?php echo e(ucfirst($filterKey)); ?>:
                        <span class="capitalize font-semibold"
                              x-text="filterValue === 'all' ? 'Semua' : filterValue">
                        </span>
                        <svg class="w-4 h-4 text-gray-500"
                             fill="none" stroke="currentColor" stroke-width="2"
                             viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M19 9l-7 7-7-7"/>
                        </svg>
                    </button>

                    <div
                        x-show="open"
                        x-transition
                        @click.outside="open = false"
                        class="absolute z-20 mt-2 w-48 rounded-md
                               border border-gray-200 bg-white shadow-md overflow-hidden"
                        style="display: none"
                    >
                        <button
                            @click="filterValue = 'all'; open = false"
                            class="block w-full px-4 py-2 text-left text-sm text-gray-700
                                   hover:bg-gray-50 transition"
                        >
                            Semua <?php echo e(ucfirst($filterKey)); ?>

                        </button>

                        <?php $__currentLoopData = $filterOptions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <button
                                @click="filterValue = '<?php echo e($option); ?>'; open = false"
                                class="block w-full px-4 py-2 text-left text-sm capitalize text-gray-700
                                       hover:bg-gray-50 transition"
                            >
                                <?php echo e($option); ?>

                            </button>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endif; ?>

            
            <?php if(count($searchKeys) > 0): ?>
                <input
                    x-model="search"
                    type="text"
                    placeholder="Cari <?php echo e(implode(', ', $searchKeys)); ?>..."
                    class="h-9 w-64 rounded-md border border-gray-300 bg-white px-3 text-sm
                           placeholder:text-gray-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500
                           "
                />
            <?php endif; ?>
        </div>

        
        <?php if(isset($header)): ?>
            <div class="shrink-0">
                <?php echo e($header); ?>

            </div>
        <?php endif; ?>
    </div>

    
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-200 bg-gray-50/50">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">
                        No
                    </th>

                    <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">
                            <?php echo e($column['label']); ?>

                        </th>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                    <?php if(isset($actions)): ?>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">
                            Aksi
                        </th>
                    <?php endif; ?>
                </tr>
            </thead>

            <tbody>
                <template x-for="(item, index) in paginatedItems" :key="item.user_id ?? item.id ?? index">
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500"
                            x-text="startIndex + index">
                        </td>

                        <?php $__currentLoopData = $columns; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $column): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <td class="px-6 py-4 text-sm text-gray-700 <?php echo e($column['class'] ?? ''); ?>">
                                <?php if(isset($column['badge'])): ?>
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                        :class="{
                                            <?php $__currentLoopData = $column['badge']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value => $color): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                '<?php echo e($color); ?>': item['<?php echo e($column['key']); ?>'] === '<?php echo e($value); ?>',
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        }"
                                        x-text="item['<?php echo e($column['key']); ?>']"
                                    ></span>
                                <?php else: ?>
                                    <span x-text="item['<?php echo e($column['key']); ?>']"></span>
                                <?php endif; ?>
                            </td>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if(isset($actions)): ?>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    <?php echo e($actions); ?>

                                </div>
                            </td>
                        <?php endif; ?>
                    </tr>
                </template>

                
                <tr x-show="filteredItems.length === 0" style="display:none">
                    <td colspan="<?php echo e(count($columns) + 2); ?>" class="px-6 py-16 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <svg class="w-14 h-14 text-gray-300"
                                 fill="none" stroke="currentColor" stroke-width="1.5"
                                 viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                      d="M20 12H4"/>
                            </svg>
                            <p class="text-sm text-gray-500 font-medium">
                                Data tidak ditemukan
                            </p>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    
    <div x-show="filteredItems.length > 0" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            
            
            <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Tampilkan:</span>
                <select 
                    x-model="perPage"
                    @change="changePerPage(perPage)"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-gray-500/20 focus:border-gray-500 bg-white"
                >
                    <template x-for="option in perPageOptions" :key="option">
                        <option :value="option" x-text="option === 'all' ? 'Semua' : option"></option>
                    </template>
                </select>
                <span class="text-sm text-gray-600">
                    dari <span x-text="filteredItems.length" class="font-semibold text-gray-700"></span> data
                </span>
            </div>

            
            <div class="text-sm text-gray-600">
                Menampilkan 
                <span x-text="startIndex" class="font-semibold text-gray-700"></span> - 
                <span x-text="endIndex" class="font-semibold text-gray-700"></span>
            </div>

            
            <div class="flex items-center gap-2" x-show="perPage !== 'all'" style="display: none">
                <button 
                    @click="changePage(currentPage - 1)"
                    :disabled="currentPage === 1"
                    :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-white hover:shadow-sm'"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm transition bg-white"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                    </svg>
                </button>
                
                <div class="flex gap-1">
                    <template x-for="page in Array.from({length: totalPages}, (_, i) => i + 1)" :key="page">
                        <button 
                            x-show="showPageButton(page)"
                            @click="changePage(page)"
                            :class="page === currentPage ? 'bg-gray-900 text-white border-gray-900' : 'hover:bg-white hover:shadow-sm bg-white'"
                            class="w-9 h-9 border border-gray-300 rounded-lg text-sm transition font-medium"
                            x-text="page"
                        ></button>
                    </template>
                </div>

                <button 
                    @click="changePage(currentPage + 1)"
                    :disabled="currentPage === totalPages"
                    :class="currentPage === totalPages ? 'opacity-50 cursor-not-allowed' : 'hover:bg-white hover:shadow-sm'"
                    class="px-3 py-1.5 border border-gray-300 rounded-lg text-sm transition bg-white"
                >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </button>
            </div>
        </div>
    </div>

    
    <?php if(isset($footer)): ?>
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            <?php echo e($footer); ?>

        </div>
    <?php endif; ?>
</div>
<?php /**PATH D:\Ums\semester7\Skripsi\aplikasi\Ayatisyarat6\app6\resources\views/components/table.blade.php ENDPATH**/ ?>