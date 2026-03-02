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

<div x-data="gradingApp">

    {{-- Back Button & Header Info --}}
    <div class="mb-6 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <a href="{{ route('guru.tugas.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <div>
                <h1 class="text-xl font-bold text-gray-900">{{ $tugas->judul_tugas }}</h1>
                <div class="flex items-center gap-2 text-sm text-gray-500 font-medium mt-1">
                    <span>Deadline: {{ $tugas->tenggat_waktu->format('d M Y') }}</span>
                    <span>&bull;</span>
                    <span>{{ $pengumpulan->count() }} Pengumpulan</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center text-gray-600 font-bold text-xl">
                {{ $stats['total'] }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Total Siswa</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center text-blue-600 font-bold text-xl">
                {{ $stats['graded'] }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Sudah Dinilai</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center text-amber-600 font-bold text-xl">
                {{ $stats['ungraded'] }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Belum Dinilai</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center text-emerald-600 font-bold text-xl">
                {{ $stats['avg'] }}
            </div>
            <div>
                <p class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Rata-rata Nilai</p>
            </div>
        </div>
    </div>

    {{-- Submissions Table --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Daftar Pengumpulan</h2>
        </div>

        @if($pengumpulan->isEmpty())
            <div class="p-16 text-center">
                <div class="text-5xl mb-4">📭</div>
                <p class="text-base font-bold text-gray-400">Belum ada siswa yang mengumpulkan</p>
            </div>
        @else
            <div class="overflow-x-auto">
                <table class="w-full text-sm">
                    <thead class="border-b border-gray-200 bg-gray-50/50">
                        <tr>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Nama Siswa</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Dikumpulkan</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-center">Status</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-center">Nilai</th>
                            <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pengumpulan as $submission)
                            @php
                                $hasFile = !empty($submission->file_jawaban);
                                $isGraded = $submission->nilai !== null;
                                $ext = $hasFile ? strtolower(pathinfo($submission->file_jawaban, PATHINFO_EXTENSION)) : '';
                                $isVideo = in_array($ext, ['mp4', 'webm', 'mov', 'avi']);
                            @endphp
                            <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                                <td class="px-6 py-4">
                                    <div class="flex items-center gap-3">
                                        <div class="w-8 h-8 rounded-full bg-emerald-100 flex items-center justify-center text-emerald-700 font-bold shrink-0">
                                            {{ strtoupper(substr($submission->user->name, 0, 1)) }}
                                        </div>
                                        <div>
                                            <p class="text-sm font-bold text-gray-800">{{ $submission->user->name }}</p>
                                            <p class="text-[11px] text-gray-500">{{ $submission->user->email }}</p>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 text-gray-600">
                                    <p class="text-xs font-semibold">{{ $submission->created_at->format('d M Y') }}</p>
                                    <p class="text-[11px] text-gray-400">{{ $submission->created_at->format('H:i') }}</p>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if(!$hasFile)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-gray-100 text-gray-500">
                                            Belum Kumpul
                                        </span>
                                    @elseif(!$isGraded)
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-amber-100 text-amber-700">
                                            Menunggu Nilai
                                        </span>
                                    @else
                                        <span class="inline-flex items-center px-2.5 py-1 rounded-full text-[10px] font-bold bg-emerald-100 text-emerald-700">
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @if($isGraded)
                                        <span class="text-lg font-black text-emerald-600">{{ $submission->nilai }}</span>
                                    @else
                                        <span class="text-gray-300 font-bold">—</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-center">
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
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-lg text-xs font-bold transition-all duration-200
                                                  {{ $isGraded ? 'bg-gray-100 text-gray-600 hover:bg-gray-200' : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm' }}">
                                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                            </svg>
                                            {{ $isGraded ? 'Edit' : 'Nilai' }}
                                        </button>
                                    @else
                                        <span class="text-[11px] text-gray-300 font-semibold">Tidak ada file</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
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
