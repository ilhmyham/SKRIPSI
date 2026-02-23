@extends('layouts.siswa')

@section('title', 'Materi Belajar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative">

    {{-- ── MODULE NAV ── --}}
    <div class="flex justify-between items-center mb-6">
        @if($previousModule)
            <a href="{{ route('siswa.materi.index', ['module' => $previousModule->id]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-lg border-2 border-gray-200 text-gray-700 hover:bg-gray-50 transition-all">
                <x-icon name="chevron-left" class="w-5 h-5" />
                <span class="text-sm font-medium hidden sm:inline">Sebelumnya</span>
            </a>
        @else
            <div class="w-24"></div>
        @endif

        <div class="text-center">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Modul Aktif</span>
            <h2 class="text-lg md:text-xl font-bold text-gray-900">{{ $currentModule->nama_modul ?? 'Modul' }}</h2>
            <div class="text-xs text-gray-500 mt-1">Halaman {{ $currentPosition }} dari {{ $totalModules }}</div>
        </div>

        @if($nextModule)
            <a href="{{ route('siswa.materi.index', ['module' => $nextModule->id]) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-all shadow-md hover:shadow-lg">
                <span class="text-sm font-medium hidden sm:inline">Berikutnya</span>
                <x-icon name="chevron-right" class="w-5 h-5" />
            </a>
        @else
            <div class="w-24"></div>
        @endif
    </div>

    {{-- ── HERO BANNER ── --}}
    <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 rounded-2xl p-6 md:p-8 mb-6 shadow-xl text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-bold mb-2">Mari Belajar Huruf Hijaiyah</h1>
            <p class="text-emerald-50 text-sm md:text-base max-w-2xl">
                Klik pada salah satu kotak huruf di bawah ini untuk melihat video isyarat tangan dan cara membacanya.
            </p>
        </div>
    </div>

    {{-- ── HOW TO LEARN TIP BOX ── --}}
    <div x-data="{ open: ! localStorage.getItem('tipDismissed') }" class="mb-6">
        <div x-show="open"
             x-transition:enter="transition ease-out duration-200"
             x-transition:enter-start="opacity-0 -translate-y-2"
             x-transition:enter-end="opacity-100 translate-y-0"
             x-transition:leave="transition ease-in duration-150"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="bg-blue-50 border border-blue-200 rounded-xl px-5 py-4 flex items-start gap-4"
             style="display: none;"
        >
            {{-- Icon --}}
            <div class="flex-shrink-0 w-9 h-9 bg-blue-100 rounded-full flex items-center justify-center text-blue-600 font-black text-sm">
                ?
            </div>
            {{-- Content --}}
            <div class="flex-1 min-w-0">
                <p class="text-sm font-semibold text-blue-900 mb-1">Cara Belajar</p>
                <ol class="text-xs text-blue-700 space-y-1 list-decimal list-inside">
                    <li>Klik kotak huruf untuk membuka video isyarat</li>
                    <li>Tonton video sampai selesai lalu praktekkan</li>
                    <li>Klik tombol <strong>"Tandai Selesai"</strong> — kartu akan berubah hijau ✓</li>
                </ol>
                @if($hasKategori)
                <p class="mt-2 text-xs text-blue-600">
                    💡 Gunakan <strong>tab kategori</strong> di atas untuk berpindah antar topik dalam modul ini.
                </p>
                @endif
            </div>
            {{-- Dismiss --}}
            <button
                @click="open = false; localStorage.setItem('tipDismissed', '1')"
                class="flex-shrink-0 text-blue-400 hover:text-blue-600 transition"
                title="Tutup panduan"
            >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>


    {{-- ── MATERI GRID ── --}}
    <div class="mb-12">
        @if($materis->count() > 0)

            @if($hasKategori)
                {{-- ── TABBED VIEW (Iqra 2–6) ── --}}
                <div x-data="materiPagination()" x-cloak>

                    {{-- Tab Pills — rendered from controller data, no PHP in view --}}
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-2 mb-6 flex gap-2 overflow-x-auto">
                        @foreach($kategoriList as $kategori)
                            @if(isset($materiData[$kategori]) && count($materiData[$kategori]) > 0)
                                <button
                                    @click="activeTab = '{{ $kategori }}'; currentPage = 1"
                                    :class="activeTab === '{{ $kategori }}' ? 'bg-emerald-500 text-white shadow-md' : 'bg-gray-50 text-gray-600 hover:bg-gray-100'"
                                    class="flex-1 min-w-[100px] px-6 py-3 rounded-xl font-bold text-sm uppercase tracking-wide transition-all duration-200 whitespace-nowrap"
                                >
                                    {{ $kategoriInfo[$kategori]['label'] }}
                                </button>
                            @endif
                        @endforeach
                    </div>

                    {{-- Tab Panels --}}
                    @foreach($kategoriList as $kategori)
                        @if(isset($materiData[$kategori]) && count($materiData[$kategori]) > 0)
                            <div x-show="activeTab === '{{ $kategori }}'"
                                 x-transition:enter="transition ease-out duration-200"
                                 x-transition:enter-start="opacity-0 scale-95"
                                 x-transition:enter-end="opacity-100 scale-100">

                                {{-- Section Header --}}
                                <div class="flex items-center gap-3 mb-6">
                                    <div class="h-1.5 w-10 bg-emerald-500 rounded-full"></div>
                                    <h3 class="text-xl font-bold text-gray-800 uppercase tracking-wider">
                                        {{ $kategoriInfo[$kategori]['fullLabel'] }}
                                    </h3>
                                    <div class="h-0.5 flex-1 bg-gray-200 rounded"></div>
                                    <span class="text-sm text-gray-500 font-medium bg-gray-50 px-3 py-1 rounded-full">
                                        {{ count($materiData[$kategori]) }} materi
                                    </span>
                                </div>

                                {{-- Materi Grid --}}
                                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2.5 md:gap-4 mb-6">
                                    <template x-for="materi in getPaginatedItems('{{ $kategori }}')" :key="materi.id">
                                        <button
                                            @click="openModalFromAlpine(materi)"
                                            class="group relative bg-white border-2 border-gray-200 rounded-xl p-2.5 hover:border-emerald-400 hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center aspect-square text-center focus:outline-none focus:ring-4 focus:ring-emerald-200"
                                        >
                                            {{-- Completion Badge --}}
                                            <template x-if="materi.is_completed">
                                                <div class="absolute top-1.5 right-1.5 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center shadow-md">
                                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                                    </svg>
                                                </div>
                                            </template>

                                            <div class="text-4xl md:text-5xl font-bold text-gray-800 mb-1 group-hover:scale-110 transition-transform duration-300 hijaiyah"
                                                 x-text="materi.huruf_hijaiyah || '?'"></div>
                                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide group-hover:text-emerald-600 leading-tight"
                                                 x-text="materi.judul_materi"></div>
                                            <span class="absolute bottom-2 text-[10px] text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity">
                                                Klik untuk belajar
                                            </span>
                                        </button>
                                    </template>
                                </div>

                                {{-- Pagination --}}
                                <div class="flex items-center justify-center gap-2 flex-wrap">
                                    <button
                                        @click="currentPage > 1 && currentPage--"
                                        :disabled="currentPage === 1"
                                        :class="currentPage === 1 ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                                        class="px-4 py-2 bg-white border-2 border-gray-200 rounded-lg font-medium text-gray-700 transition-all">
                                        ← Previous
                                    </button>

                                    <template x-for="page in getTotalPages('{{ $kategori }}')" :key="page">
                                        <button
                                            @click="currentPage = page"
                                            :class="currentPage === page ? 'bg-emerald-500 text-white border-emerald-500' : 'bg-white text-gray-700 border-gray-200 hover:bg-gray-50'"
                                            class="w-10 h-10 border-2 rounded-lg font-bold transition-all"
                                            x-text="page"></button>
                                    </template>

                                    <button
                                        @click="currentPage < getTotalPages('{{ $kategori }}') && currentPage++"
                                        :disabled="currentPage >= getTotalPages('{{ $kategori }}')"
                                        :class="currentPage >= getTotalPages('{{ $kategori }}') ? 'opacity-50 cursor-not-allowed' : 'hover:bg-gray-50'"
                                        class="px-4 py-2 bg-white border-2 border-gray-200 rounded-lg font-medium text-gray-700 transition-all">
                                        Next →
                                    </button>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>

            @else
                {{-- ── SIMPLE GRID (Iqra 1 — no kategori) ── --}}
                <div class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-5 lg:grid-cols-6 gap-2.5 md:gap-4">
                    @foreach($materis as $materi)
                        <button
                            onclick="openModalFromElement(this)"
                            data-id="{{ $materi->id }}"
                            data-huruf="{{ $materi->huruf_hijaiyah }}"
                            data-judul="{{ $materi->judul_materi }}"
                            data-video="{{ $materi->file_video }}"
                            data-desc="{{ $materi->deskripsi }}"
                            data-gambar="{{ $materi->file_path ? asset('storage/' . $materi->file_path) : '' }}"
                            data-complete-url="{{ route('siswa.materi.complete', $materi->id) }}"
                            class="group relative bg-white border-2 border-gray-200 rounded-xl p-2.5 hover:border-emerald-400 hover:shadow-lg transition-all duration-300 flex flex-col items-center justify-center aspect-square text-center focus:outline-none focus:ring-4 focus:ring-emerald-200"
                        >
                            @if($materi->progress?->first()?->status == 'selesai')
                                <div class="absolute top-1.5 right-1.5 w-5 h-5 bg-emerald-500 rounded-full flex items-center justify-center shadow-md">
                                    <svg class="w-3 h-3 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </div>
                            @endif
                            <div class="text-4xl md:text-5xl font-bold text-gray-800 mb-1 group-hover:scale-110 transition-transform duration-300 hijaiyah">
                                {{ $materi->huruf_hijaiyah ?? '?' }}
                            </div>
                            <div class="text-[10px] font-bold text-gray-400 uppercase tracking-wide group-hover:text-emerald-600 leading-tight">
                                {{ $materi->judul_materi }}
                            </div>
                        </button>
                    @endforeach
                </div>
            @endif

        @else
            <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-gray-500 font-medium">Belum ada materi pada modul ini.</p>
            </div>
        @endif
    </div>

    {{-- ── MODULE QUICK NAV ── --}}
    <div class="border-t border-gray-200 pt-8">
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Navigasi Cepat Modul</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($modules as $module)
                <a href="{{ route('siswa.materi.index', ['module' => $module->id]) }}"
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentModule && $currentModule->id == $module->id ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    {{ $module->nama_modul }}
                </a>
            @endforeach
        </div>
    </div>

</div>

{{-- ── LEARNING MODAL ── --}}
<div id="learningModal" class="fixed inset-0 z-[60] hidden" role="dialog" aria-modal="true" aria-labelledby="modal-title">
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto pb-20 sm:pb-0">
        <div class="flex min-h-full items-center justify-center p-4 sm:p-0">
            <div class="relative w-full transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all my-4 sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200">

                {{-- Modal Header --}}
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-gray-100">
                    <h3 id="modal-title" class="text-lg font-bold leading-6 text-gray-900">
                        Belajar Huruf <span id="modalHurufTitle" class="text-emerald-600 text-xl ml-1 font-arabic"></span>
                    </h3>
                    <button type="button" onclick="closeModal()" class="rounded-full p-1 hover:bg-gray-200 transition-colors focus:outline-none" aria-label="Tutup modal">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                {{-- Modal Body --}}
                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">

                        {{-- Video Panel --}}
                        <div class="space-y-4">
                            <div id="videoWrapper" class="w-full aspect-video bg-black rounded-xl overflow-hidden shadow-lg relative">
                                <div id="player"></div>
                            </div>
                            <p class="text-xs text-center text-gray-500 bg-gray-50 py-2 rounded-lg">
                                <span class="font-bold">Tips:</span> Perhatikan gerakan tangan dan posisi jari.
                            </p>
                        </div>

                        {{-- Info Panel --}}
                        <div class="flex flex-col h-full">
                            <div class="flex-1 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center mb-4 p-4 min-h-[150px]">
                                <img id="staticImage" src="" alt="Panduan Isyarat" class="max-h-48 object-contain hidden">
                                <span id="noImageText" class="text-gray-400 text-sm">Tidak ada gambar panduan</span>
                            </div>
                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mb-4">
                                <h4 class="text-sm font-bold text-blue-800 mb-1">Keterangan:</h4>
                                <p id="modalDeskripsi" class="text-sm text-blue-700 leading-relaxed">-</p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Modal Footer --}}
                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <form id="formSelesai" action="" method="POST">
                        @csrf
                        @method('POST')
                        <button type="submit"
                                class="inline-flex w-full justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:ml-3 sm:w-auto transition-all active:scale-95">
                            ✅ Tandai Selesai
                        </button>
                    </form>
                    <button type="button" onclick="closeModal()"
                            class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ── ALPINE.JS PAGINATION COMPONENT ── --}}
@if($hasKategori)
<script>
function materiPagination() {
    return {
        activeTab:    '{{ $firstTab }}',
        currentPage:  1,
        itemsPerPage: 10,
        materiData:   @json($materiData),

        getPaginatedItems(kategori) {
            const items = this.materiData[kategori] ?? [];
            const start = (this.currentPage - 1) * this.itemsPerPage;
            return items.slice(start, start + this.itemsPerPage);
        },

        getTotalPages(kategori) {
            const items = this.materiData[kategori] ?? [];
            return Math.max(1, Math.ceil(items.length / this.itemsPerPage));
        },

        /**
         * Open modal directly from Alpine data — NO fake DOM element.
         * Calls the global openLearningModal() from materi-player.js.
         */
        openModalFromAlpine(materi) {
            openLearningModal({
                id:          materi.id,
                huruf:       materi.huruf_hijaiyah,
                judul:       materi.judul_materi,
                video:       materi.file_video   || '',
                desc:        materi.deskripsi    || '',
                gambar:      materi.file_path ? `/storage/${materi.file_path}` : '',
                completeUrl: `{{ url('siswa/materi') }}/${materi.id}/complete`,
            });
        },
    };
}
</script>
@endif

{{-- External player JS (YouTube + Drive logic) --}}
<script src="{{ asset('js/materi-player.js') }}" defer></script>

@endsection