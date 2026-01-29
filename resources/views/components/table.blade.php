@props([
    'items' => [],
    'columns' => [],
    'filterKey' => null,
    'filterOptions' => [],
    'searchKeys' => [],
    'itemsPerPage' => null,
])

@php
    $itemsArray = is_array($items) ? $items : $items->toArray();
@endphp

<div
    x-data="{
        filterValue: 'all',
        search: '',
        items: @js($itemsArray),
        get filteredItems() {
            return this.items.filter(item => {
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
        }
    }"
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
                           placeholder:text-gray-400
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
                <template x-for="(item, index) in filteredItems" :key="item.user_id ?? index">
                    <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                        <td class="px-6 py-4 text-sm text-gray-500"
                            x-text="index + 1">
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

    {{-- ================= FOOTER ================= --}}
    @isset($footer)
        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
            {{ $footer }}
        </div>
    @endisset
</div>
