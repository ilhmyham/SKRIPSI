@extends('layouts.siswa')

@section('title', $tugas->judul_tugas)

@section('content')
<div class="pb-24 min-h-screen bg-gray-50">

    {{-- ── HERO HEADER ── --}}
    @php
        $isLate      = $tugas->deadline < now();
        $isSubmitted = isset($pengumpulan) && $pengumpulan;
        $isGraded    = $isSubmitted && $pengumpulan->nilai !== null;
    @endphp

    <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-900 pt-10 pb-20 px-5 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-purple-300 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-2xl mx-auto relative z-10">
            <a href="{{ route('siswa.tugas.index') }}" class="inline-flex items-center gap-1.5 text-emerald-200 hover:text-white text-sm font-semibold mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Tugas
            </a>
            <p class="text-emerald-200 text-xs font-bold uppercase tracking-widest mb-1.5">Tugas Siswa</p>
            <h1 class="text-white text-2xl sm:text-3xl font-black tracking-tight leading-tight">{{ $tugas->judul_tugas }}</h1>

            {{-- Deadline badge --}}
            <div class="inline-flex items-center gap-2 mt-3 px-3 py-1.5 rounded-full text-sm font-bold
                {{ $isLate ? 'bg-red-500/30 text-red-200' : 'bg-white/15 text-white' }}">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                Deadline: {{ $tugas->deadline->format('d M Y') }}
                @if($isLate)
                    <span class="ml-1 font-black">• Terlambat</span>
                @endif
            </div>
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 -mt-12 relative z-10 space-y-4">

        {{-- Deskripsi Tugas --}}
        @if($tugas->deskripsi_tugas)
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50/50">
                <h2 class="text-sm font-black text-gray-500 uppercase tracking-wider">Deskripsi Tugas</h2>
            </div>
            <div class="px-6 py-5">
                <p class="text-gray-700 leading-relaxed text-sm sm:text-base">{{ $tugas->deskripsi_tugas }}</p>
            </div>
        </div>
        @endif

        {{-- Status: Sudah dikumpulkan --}}
        @if($isSubmitted)
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50">
                <div class="flex items-center gap-2">
                    <div class="w-6 h-6 rounded-full bg-emerald-600 flex items-center justify-center">
                        <svg class="w-3.5 h-3.5 text-white" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                    <h2 class="text-sm font-black text-emerald-800 uppercase tracking-wider">Status Pengumpulan</h2>
                </div>
            </div>
            <div class="px-6 py-5 space-y-5">

                {{-- File yang dikumpulkan --}}
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-indigo-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">File Jawaban</p>
                        <a href="{{ asset('storage/' . $pengumpulan->file_jawaban) }}"
                           target="_blank"
                           class="inline-flex items-center gap-2 text-sm font-bold text-indigo-600 hover:text-indigo-800 hover:underline transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                            Download File
                        </a>
                    </div>
                </div>

                {{-- Tanggal pengumpulan --}}
                <div class="flex items-start gap-4">
                    <div class="flex-shrink-0 w-10 h-10 rounded-xl bg-gray-100 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-1">Dikumpulkan Pada</p>
                        <p class="text-sm font-semibold text-gray-700">{{ $pengumpulan->created_at->format('d M Y, H:i') }} WIB</p>
                    </div>
                </div>

                {{-- Nilai --}}
                <div class="border-t border-gray-100 pt-5">
                    @if($isGraded)
                        <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mb-3">Nilai dari Guru</p>
                        <div class="flex items-center gap-4">
                            <div class="text-6xl font-black
                                {{ $pengumpulan->nilai >= 80 ? 'text-emerald-600' : ($pengumpulan->nilai >= 60 ? 'text-blue-600' : 'text-orange-500') }}">
                                {{ $pengumpulan->nilai }}
                            </div>
                            <div>
                                <div class="text-sm font-bold
                                    {{ $pengumpulan->nilai >= 80 ? 'text-emerald-600' : ($pengumpulan->nilai >= 60 ? 'text-blue-600' : 'text-orange-500') }}">
                                    {{ $pengumpulan->nilai >= 80 ? 'Sangat Baik' : ($pengumpulan->nilai >= 60 ? 'Baik' : 'Perlu Perbaikan') }}
                                </div>
                                <div class="text-xs text-gray-400 mt-0.5">Dari skala 100</div>
                            </div>
                        </div>
                        {{-- Progress bar nilai --}}
                        <div class="mt-4 w-full bg-gray-100 rounded-full h-2.5">
                            <div class="h-2.5 rounded-full
                                {{ $pengumpulan->nilai >= 80 ? 'bg-emerald-500' : ($pengumpulan->nilai >= 60 ? 'bg-blue-500' : 'bg-orange-500') }}"
                                 style="width: {{ $pengumpulan->nilai }}%">
                            </div>
                        </div>
                    @else
                        <div class="flex items-center gap-3 p-4 bg-amber-50 rounded-2xl border border-amber-200">
                            <div class="flex-shrink-0 w-8 h-8 rounded-full bg-amber-100 flex items-center justify-center">
                                <svg class="w-4 h-4 text-amber-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-amber-800">Menunggu Penilaian</p>
                                <p class="text-xs text-amber-600 mt-0.5">Guru sedang memeriksa tugasmu</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Belum dikumpulkan: tampilkan form --}}
        @else
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100 bg-emerald-50">
                <h2 class="text-sm font-black text-emerald-800 uppercase tracking-wider">Kumpulkan Tugas</h2>
            </div>
            <div class="px-6 py-6">
                @if($isLate)
                <div class="flex items-center gap-3 p-4 bg-red-50 rounded-2xl border border-red-200 mb-5">
                    <svg class="w-5 h-5 text-red-500 flex-shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                    </svg>
                    <p class="text-sm font-bold text-red-700">Deadline sudah lewat. Pengumpulan tetap bisa dilakukan.</p>
                </div>
                @endif

                <form method="POST" action="{{ route('siswa.tugas.submit', $tugas) }}" enctype="multipart/form-data" class="space-y-5">
                    @csrf

                    {{-- Dropzone area --}}
                    <div x-data="{ fileName: '', dragging: false }"
                         @dragover.prevent="dragging = true"
                         @dragleave.prevent="dragging = false"
                         @drop.prevent="dragging = false; fileName = $event.dataTransfer.files[0]?.name; $refs.fileInput.files = $event.dataTransfer.files">
                        <label class="block mb-2 text-sm font-bold text-gray-700">File Tugas</label>
                        <div :class="dragging ? 'border-indigo-400 bg-indigo-50' : 'border-gray-200 bg-gray-50 hover:border-indigo-300'"
                             class="relative border-2 border-dashed rounded-2xl p-8 text-center transition-all duration-200 cursor-pointer"
                             @click="$refs.fileInput.click()">
                            <input x-ref="fileInput" type="file" name="file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                                   class="hidden"
                                   @change="fileName = $event.target.files[0]?.name"
                                   @error('file') x-bind:class="'border-red-400 bg-red-50'" @enderror>

                            <template x-if="!fileName">
                                <div>
                                    <div class="flex justify-center mb-3">
                                        <div class="w-14 h-14 rounded-2xl bg-indigo-100 flex items-center justify-center">
                                            <svg class="w-7 h-7 text-indigo-500" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5"/>
                                            </svg>
                                        </div>
                                    </div>
                                    <p class="text-sm font-bold text-gray-600">Klik atau drag & drop file di sini</p>
                                    <p class="text-xs text-gray-400 mt-1">PDF, DOC, DOCX, JPG, PNG · Maks 10MB</p>
                                </div>
                            </template>
                            <template x-if="fileName">
                                <div class="flex items-center justify-center gap-3">
                                    <div class="w-10 h-10 rounded-xl bg-emerald-100 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                        </svg>
                                    </div>
                                    <div class="text-left">
                                        <p class="text-sm font-bold text-gray-800" x-text="fileName"></p>
                                        <p class="text-xs text-emerald-600 font-semibold">Siap dikumpulkan</p>
                                    </div>
                                </div>
                            </template>
                        </div>
                        @error('file')
                            <p class="mt-2 text-xs font-semibold text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <button type="submit"
                            class="w-full flex items-center justify-center gap-3 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-black text-base py-4 rounded-2xl shadow-lg shadow-emerald-200 transition-all duration-200">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Kumpulkan Tugas
                    </button>
                </form>
            </div>
        </div>
        @endif

    </div>
</div>
@endsection
