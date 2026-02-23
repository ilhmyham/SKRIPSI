@extends('layouts.guru')

@section('title', 'Pengumpulan — ' . $tugas->judul_tugas)
@section('page-title', 'Pengumpulan Tugas')

@section('content')


{{-- Register Alpine component BEFORE Alpine evaluates x-data --}}
<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('gradingApp', () => ({
            open: false,
            current: {
                id: null, name: '', file: '', isVideo: false,
                nilai: null, catatan: '', gradeUrl: ''
            },
            openGrading(data) {
                this.current = data;
                this.open = true;
                document.body.style.overflow = 'hidden';
            },
            closeGrading() {
                this.open = false;
                document.body.style.overflow = '';
            }
        }));
    });
</script>

{{-- ── PAGE WRAPPER ── --}}
<div class="min-h-screen bg-gray-50/80" x-data="gradingApp">

    {{-- ── HEADER STRIP ── --}}
    <div class="bg-white border-b border-gray-200 sticky top-0 z-30">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 h-16 flex items-center gap-4">
            <a href="{{ route('guru.tugas.index') }}"
               class="w-9 h-9 rounded-xl bg-gray-100 hover:bg-emerald-50 hover:text-emerald-700 flex items-center justify-center text-gray-500 transition-all duration-200">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
            </a>
            <div class="flex-1 min-w-0">
                <h1 class="text-base font-black text-gray-900 truncate">{{ $tugas->judul_tugas }}</h1>
                <p class="text-xs text-gray-400 font-semibold">
                    Deadline: {{ $tugas->deadline->format('d M Y') }}
                    &nbsp;·&nbsp;
                    {{ $pengumpulan->count() }} pengumpulan
                </p>
            </div>
        </div>
    </div>

    <div class="max-w-5xl mx-auto px-4 sm:px-6 py-6 space-y-5">

        {{-- ── SUMMARY CHIPS ── --}}
        <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
            @foreach([
                ['val' => $stats['total'],    'label' => 'Total Siswa',    'color' => 'from-emerald-500 to-emerald-700'],
                ['val' => $stats['graded'],   'label' => 'Sudah Dinilai',  'color' => 'from-blue-500 to-blue-700'],
                ['val' => $stats['ungraded'], 'label' => 'Belum Dinilai',  'color' => 'from-amber-500 to-amber-600'],
                ['val' => $stats['avg'],      'label' => 'Rata-rata Nilai','color' => 'from-violet-500 to-violet-700'],
            ] as $chip)
                <div class="bg-white rounded-2xl p-4 border border-gray-100 shadow-sm text-center">
                    <p class="text-2xl font-black bg-gradient-to-br {{ $chip['color'] }} bg-clip-text text-transparent leading-none">
                        {{ $chip['val'] }}
                    </p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">{{ $chip['label'] }}</p>
                </div>
            @endforeach
        </div>

        {{-- ── SUBMISSIONS TABLE ── --}}
        @if($pengumpulan->isEmpty())
            <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-16 text-center">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-base font-bold text-gray-400">Belum ada siswa yang mengumpulkan</p>
            </div>
        @else
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden">

                {{-- Table Header --}}
                <div class="grid grid-cols-12 gap-3 px-5 py-3 bg-gray-50 border-b border-gray-100 text-[11px] font-black text-gray-400 uppercase tracking-widest">
                    <div class="col-span-4">Siswa</div>
                    <div class="col-span-3">Dikumpulkan</div>
                    <div class="col-span-2 text-center">Status</div>
                    <div class="col-span-1 text-center">Nilai</div>
                    <div class="col-span-2 text-right">Aksi</div>
                </div>

                {{-- Table Rows --}}
                <div class="divide-y divide-gray-50">
                    @foreach($pengumpulan as $submission)
                        @php
                            $hasFile = !empty($submission->file_jawaban);
                            $isGraded = $submission->nilai !== null;
                            $ext = $hasFile ? strtolower(pathinfo($submission->file_jawaban, PATHINFO_EXTENSION)) : '';
                            $isVideo = in_array($ext, ['mp4', 'webm', 'mov', 'avi']);
                        @endphp
                        <div class="grid grid-cols-12 gap-3 px-5 py-4 items-center hover:bg-emerald-50/30 transition-colors duration-150">

                            {{-- Siswa --}}
                            <div class="col-span-4 flex items-center gap-3">
                                <div class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-700 flex items-center justify-center text-sm font-black text-white shrink-0">
                                    {{ substr($submission->user->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <p class="text-sm font-bold text-gray-800 truncate">{{ $submission->user->name }}</p>
                                    <p class="text-[11px] text-gray-400 truncate">{{ $submission->user->email }}</p>
                                </div>
                            </div>

                            {{-- Tanggal --}}
                            <div class="col-span-3">
                                <p class="text-xs font-semibold text-gray-600">{{ $submission->created_at->format('d M Y') }}</p>
                                <p class="text-[11px] text-gray-400">{{ $submission->created_at->format('H:i') }}</p>
                            </div>

                            {{-- Status Badge --}}
                            <div class="col-span-2 flex justify-center">
                                @if(!$hasFile)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-gray-100 text-gray-500">
                                        <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                        Belum Kumpul
                                    </span>
                                @elseif(!$isGraded)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span>
                                        Menunggu Nilai
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Selesai
                                    </span>
                                @endif
                            </div>

                            {{-- Nilai --}}
                            <div class="col-span-1 text-center">
                                @if($isGraded)
                                    <span class="text-lg font-black bg-gradient-to-br from-emerald-600 to-emerald-800 bg-clip-text text-transparent">
                                        {{ $submission->nilai }}
                                    </span>
                                @else
                                    <span class="text-gray-300 font-bold text-sm">—</span>
                                @endif
                            </div>

                            {{-- Aksi --}}
                            <div class="col-span-2 flex items-center justify-end gap-2">
                                @if($hasFile)
                                    <button @click="openGrading({{ json_encode([
                                        'id'           => $submission->id,
                                        'name'         => $submission->user->name,
                                        'file'         => asset('storage/' . $submission->file_jawaban),
                                        'isVideo'      => $isVideo,
                                        'nilai'        => $submission->nilai,
                                        'catatan'      => $submission->catatan_guru,
                                        'gradeUrl'     => route('guru.submissions.grade', $submission),
                                    ]) }})"
                                       class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all duration-200
                                              {{ $isGraded
                                                  ? 'bg-gray-100 text-gray-600 hover:bg-emerald-50 hover:text-emerald-700'
                                                  : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm shadow-emerald-300/50' }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        {{ $isGraded ? 'Edit' : 'Nilai' }}
                                    </button>
                                @else
                                    <span class="text-[11px] text-gray-300 font-semibold">Tidak ada file</span>
                                @endif
                            </div>

                        </div>
                    @endforeach
                </div>
            </div>
        @endif

    </div>

    {{-- ══════════════════════════════════════════
         GRADING MODAL
    ══════════════════════════════════════════ --}}
    <div x-show="open"
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         class="fixed inset-0 z-50 flex items-end sm:items-center justify-center p-0 sm:p-4"
         style="display: none;">

        {{-- Backdrop --}}
        <div class="absolute inset-0 bg-black/50 backdrop-blur-sm" @click="closeGrading()"></div>

        {{-- Modal Panel — no separate x-show, rides the parent transition --}}
        <div class="relative bg-white rounded-t-3xl sm:rounded-3xl shadow-2xl w-full sm:max-w-4xl max-h-[92vh] flex flex-col overflow-hidden">


            {{-- Modal Header --}}
            <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 shrink-0">
                <div>
                    <h2 class="text-base font-black text-gray-900">Penilaian Tugas</h2>
                    <p class="text-xs text-gray-400 font-semibold mt-0.5" x-text="current.name"></p>
                </div>
                <button @click="closeGrading()"
                        class="w-8 h-8 rounded-xl bg-gray-100 hover:bg-red-50 hover:text-red-500 flex items-center justify-center text-gray-400 transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </button>
            </div>

            {{-- Modal Body: 2 column --}}
            <div class="flex-1 overflow-y-auto">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-0 h-full">

                    {{-- LEFT: File Viewer --}}
                    <div class="bg-gray-900 flex flex-col items-center justify-center min-h-64 sm:min-h-0 relative">
                        <template x-if="current.isVideo">
                            <video :src="current.file"
                                   controls
                                   class="w-full h-full max-h-96 sm:max-h-full object-contain">
                            </video>
                        </template>
                        <template x-if="!current.isVideo && current.file">
                            <div class="flex flex-col items-center gap-4 p-8 text-center">
                                <div class="w-20 h-20 bg-white/10 rounded-2xl flex items-center justify-center text-4xl">
                                    📄
                                </div>
                                <p class="text-white/70 text-sm font-semibold">File Jawaban</p>
                                <a :href="current.file" target="_blank"
                                   class="inline-flex items-center gap-2 px-4 py-2.5 bg-emerald-600 hover:bg-emerald-500 text-white text-sm font-bold rounded-xl transition-colors duration-200">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"/>
                                    </svg>
                                    Download File
                                </a>
                            </div>
                        </template>
                    </div>

                    {{-- RIGHT: Grading Form --}}
                    <div class="p-6 flex flex-col gap-5">

                        {{-- Current score display --}}
                        <div class="bg-gray-50 rounded-2xl p-4 flex items-center gap-4">
                            <div class="w-14 h-14 rounded-2xl bg-gradient-to-br from-emerald-500 to-emerald-700 flex items-center justify-center shrink-0">
                                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4M7.835 4.697a3.42 3.42 0 001.946-.806 3.42 3.42 0 014.438 0 3.42 3.42 0 001.946.806 3.42 3.42 0 013.138 3.138 3.42 3.42 0 00.806 1.946 3.42 3.42 0 010 4.438 3.42 3.42 0 00-.806 1.946 3.42 3.42 0 01-3.138 3.138 3.42 3.42 0 00-1.946.806 3.42 3.42 0 01-4.438 0 3.42 3.42 0 00-1.946-.806 3.42 3.42 0 01-3.138-3.138 3.42 3.42 0 00-.806-1.946 3.42 3.42 0 010-4.438 3.42 3.42 0 00.806-1.946 3.42 3.42 0 013.138-3.138z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">Nilai Saat Ini</p>
                                <p class="text-2xl font-black text-gray-800 leading-none mt-0.5"
                                   x-text="current.nilai !== null ? current.nilai : '—'"></p>
                            </div>
                        </div>

                        {{-- Form --}}
                        <form :action="current.gradeUrl" method="POST" class="flex flex-col gap-4 flex-1">
                            @csrf

                            {{-- Nilai Input --}}
                            <div>
                                <label class="block text-xs font-black text-gray-700 uppercase tracking-widest mb-2">
                                    Nilai <span class="text-red-500">*</span>
                                    <span class="text-gray-400 normal-case font-semibold tracking-normal ml-1">(0 – 100)</span>
                                </label>
                                <div class="relative">
                                    <input type="number"
                                           name="nilai"
                                           min="0" max="100"
                                           :value="current.nilai"
                                           required
                                           placeholder="Masukkan nilai..."
                                           class="w-full px-4 py-3 text-2xl font-black text-center text-gray-800 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:outline-none focus:border-emerald-500 focus:bg-white transition-all duration-200 placeholder:text-gray-300 placeholder:text-base placeholder:font-normal">
                                </div>
                            </div>

                            {{-- Catatan Guru --}}
                            <div class="flex-1">
                                <label class="block text-xs font-black text-gray-700 uppercase tracking-widest mb-2">
                                    Catatan Guru
                                    <span class="text-gray-400 normal-case font-semibold tracking-normal ml-1">(opsional)</span>
                                </label>
                                <textarea name="catatan_guru"
                                          rows="4"
                                          :value="current.catatan"
                                          placeholder="Tulis catatan atau feedback untuk siswa..."
                                          class="w-full px-4 py-3 text-sm text-gray-700 bg-gray-50 border-2 border-gray-200 rounded-2xl focus:outline-none focus:border-emerald-500 focus:bg-white transition-all duration-200 resize-none placeholder:text-gray-300"
                                          x-text="current.catatan"></textarea>
                            </div>

                            {{-- Actions --}}
                            <div class="flex gap-3 pt-1">
                                <button type="button" @click="closeGrading()"
                                        class="flex-1 py-3 rounded-2xl text-sm font-bold text-gray-600 bg-gray-100 hover:bg-gray-200 transition-colors duration-200">
                                    Batal
                                </button>
                                <button type="submit"
                                        class="flex-1 py-3 rounded-2xl text-sm font-bold text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-300/40 transition-all duration-200 flex items-center justify-center gap-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                                    </svg>
                                    Simpan Nilai
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

@endsection
