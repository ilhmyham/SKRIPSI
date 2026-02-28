@extends('layouts.siswa')

@section('title', 'Kuis')

@section('content')
<div class="pb-24">

    {{-- ── HEADER ── --}}
    <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-900 pt-10 pb-16 px-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-amber-400/15 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>
        <div class="max-w-2xl mx-auto relative z-10">
            <p class="text-emerald-200 text-xs font-bold uppercase tracking-widest mb-1">Uji Pemahaman</p>
            <h1 class="text-white text-3xl font-black tracking-tight">Kuis Tersedia ✏️</h1>
            <p class="text-emerald-200/80 text-sm mt-1">Kerjakan kuis untuk menguji pemahamanmu</p>
        </div>
    </div>

    {{-- ── CONTENT ── --}}
    <div class="max-w-2xl mx-auto px-4 mt-4 relative z-10 space-y-3 pb-4">

        @if($kuisList->isEmpty())
            <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-14 text-center shadow-sm">
                <div class="text-5xl mb-3">📭</div>
                <p class="text-sm font-bold text-gray-400">Belum ada kuis tersedia</p>
            </div>
        @else
            @foreach($kuisList as $kuis)
                <div class="bg-white rounded-2xl border-2 border-gray-200 shadow-sm overflow-hidden hover:shadow-md transition-all duration-200">

                    <div class="p-4 flex items-start gap-4">
                        {{-- Icon --}}
                        <div class="w-11 h-11 rounded-xl bg-emerald-100 flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                            </svg>
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            {{-- Module badge --}}
                            <span class="inline-block px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700 uppercase tracking-widest mb-1.5">
                                {{ $kuis->module?->nama_modul ?? 'Modul' }}
                            </span>

                            <h3 class="text-sm font-black text-gray-800 leading-tight">{{ $kuis->judul_kuis }}</h3>

                            @if($kuis->deskripsi)
                                <p class="text-xs text-gray-400 mt-1 line-clamp-2">{{ $kuis->deskripsi }}</p>
                            @endif

                            <div class="flex items-center gap-1.5 mt-2">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span class="text-xs font-semibold text-gray-400">{{ $kuis->questions->count() }} Pertanyaan</span>
                            </div>
                        </div>

                        {{-- Action --}}
                        <a href="{{ route('siswa.kuis.show', $kuis) }}"
                           class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm shadow-emerald-300/40 transition-all duration-200">
                            Mulai
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </div>
                </div>
            @endforeach
        @endif

    </div>
</div>
@endsection
