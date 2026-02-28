@props([
    'items' => [],
    'columns' => [],
    'filterKey' => null,
    'filterOptions' => [],
    'searchKeys' => [],
    'itemsPerPage' => 5,
])

@php
    $itemsArray = is_array($items) ? $items : $items->toArray();
@endphp

<div
    x-data="{
        allItems: @js($itemsArray),
        filterValue: 'all',
        search: '',
        currentPage: 1,
        perPage: @js($itemsPerPage),
        perPageOptions: [5, 10, 20, 'all'],
        
        get filteredItems() {
            return this.allItems.filter(item => {
                const matchFilter =
                    !@js($filterKey) ||
                    this.filterValue === 'all' ||
                    item[@js($filterKey)] === this.filterValue

                const matchSearch =
                    this.search === '' ||
                    @js($searchKeys).some(key => {
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

    {{-- ================= HEADER ================= --}}
    <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100">

        {{-- LEFT: Filter + Search --}}
        <div class="flex items-center gap-3 flex-1">

            {{-- Filter --}}
            @if($filterKey && count($filterOptions) > 0)
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
                        {{ ucfirst($filterKey) }}:
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
                            Semua {{ ucfirst($filterKey) }}
                        </button>

                        @foreach($filterOptions as $option)
                            <button
                                @click="filterValue = '{{ $option }}'; open = false"
                                class="block w-full px-4 py-2 text-left text-sm capitalize text-gray-700
                                       hover:bg-gray-50 transition"
                            >
                                {{ $option }}
                            </button>
                        @endforeach
                    </div>
                </div>
            @endif

            {{-- Search --}}
            @if(count($searchKeys) > 0)
                <input
                    x-model="search"
                    type="text"
                    placeholder="Cari {{ implode(', ', $searchKeys) }}..."
                    class="h-9 w-64 rounded-md border border-gray-300 bg-white px-3 text-sm
                           placeholder:text-gray-400 focus:ring-2 focus:ring-emerald-500/20 focus:border-emerald-500
                           "
                />
            @endif
        </div>

        {{-- RIGHT: Header Action --}}
        @isset($header)
            <div class="shrink-0">
                {{ $header }}
            </div>
        @endisset
    </div>

    {{-- ================= TABLE ================= --}}
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="border-b border-gray-200 bg-gray-50/50">
                <tr>
                    <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">
                        No
                    </th>

                    @foreach($columns as $column)
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">
                            {{ $column['label'] }}
                        </th>
                    @endforeach

                    @if(isset($actions))
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">
                            Aksi
                        </th>
                    @endif
                </tr>
            </thead>

            <tbody>
                <template x-for="(item, index) in paginatedItems" :key="item.user_id ?? item.id ?? index">
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500"
                            x-text="startIndex + index">
                        </td>

                        @foreach($columns as $column)
                            <td class="px-6 py-4 text-sm text-gray-700 {{ $column['class'] ?? '' }}">
                                @if(isset($column['badge']))
                                    <span
                                        class="inline-flex items-center rounded-full px-2.5 py-0.5 text-xs font-medium"
                                        :class="{
                                            @foreach($column['badge'] as $value => $color)
                                                '{{ $color }}': item['{{ $column['key'] }}'] === '{{ $value }}',
                                            @endforeach
                                        }"
                                        x-text="item['{{ $column['key'] }}']"
                                    ></span>
                                @else
                                    <span x-text="item['{{ $column['key'] }}']"></span>
                                @endif
                            </td>
                        @endforeach

                        @if(isset($actions))
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    {{ $actions }}
                                </div>
                            </td>
                        @endif
                    </tr>
                </template>

                {{-- Empty State --}}
                <tr x-show="filteredItems.length === 0" style="display:none">
                    <td colspan="{{ count($columns) + 2 }}" class="px-6 py-16 text-center">
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

    {{-- ================= PAGINATION ================= --}}
    <div x-show="filteredItems.length > 0" class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
        <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
            
            {{-- Per Page Selector --}}
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

            {{-- Page Info --}}
            <div class="text-sm text-gray-600">
                Menampilkan 
                <span x-text="startIndex" class="font-semibold text-gray-700"></span> - 
                <span x-text="endIndex" class="font-semibold text-gray-700"></span>
            </div>

            {{-- Page Navigation --}}
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

    {{-- ================= FOOTER ================= --}}
    @isset($footer)
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $footer }}
        </div>
    @endisset
</div>
