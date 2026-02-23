@extends('layouts.siswa')

@section('title', 'Tugas Saya')

@section('content')
<div class="pb-24">

    {{-- ── HEADER ── --}}
    <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-900 pt-10 pb-16 px-5 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-amber-400/15 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>
        <div class="max-w-2xl mx-auto relative z-10">
            <p class="text-emerald-200 text-xs font-bold uppercase tracking-widest mb-1">Daftar</p>
            <h1 class="text-white text-3xl font-black tracking-tight">Tugas Saya 📋</h1>
            <p class="text-emerald-200/80 text-sm mt-1">Kerjakan dan kumpulkan sebelum deadline</p>
        </div>
    </div>

    {{-- ── CONTENT ── --}}
    <div class="max-w-2xl mx-auto px-4 mt-4 relative z-10 space-y-3 pb-4">

        @if($tugasList->isEmpty())
            <div class="bg-white rounded-2xl border border-dashed border-gray-200 p-14 text-center shadow-sm">
                <div class="text-5xl mb-3">📭</div>
                <p class="text-sm font-bold text-gray-400">Belum ada tugas tersedia</p>
            </div>
        @else
            @foreach($tugasList as $tugas)
                @php
                    $submission = $tugas->submissions->first();
                    $isOverdue  = $tugas->deadline < now() && !$submission;
                    $isGraded   = $submission && $submission->nilai !== null;
                    $isSubmitted = $submission && !$isGraded;
                @endphp

                <div class="bg-white rounded-2xl border-2 border-gray-200 shadow-sm overflow-hidden transition-all duration-200 hover:shadow-md
                    {{ $isOverdue ? 'border-red-200' : 'border-gray-100' }}">
                    
                    <div class="p-4 flex items-start gap-4">
                        {{-- Icon --}}
                        <div class="w-11 h-11 rounded-xl flex items-center justify-center shrink-0
                            {{ $isGraded ? 'bg-emerald-100' :
                               ($isSubmitted ? 'bg-blue-100' :
                               ($isOverdue ? 'bg-red-100' : 'bg-amber-100')) }}">
                            @if($isGraded)
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @elseif($isSubmitted)
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                                </svg>
                            @elseif($isOverdue)
                                <svg class="w-5 h-5 text-red-500" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                            @else
                                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            @endif
                        </div>

                        {{-- Info --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="text-sm font-black text-gray-800 leading-tight">{{ $tugas->judul_tugas }}</h3>

                            <div class="flex items-center gap-1.5 mt-1.5">
                                <svg class="w-3.5 h-3.5 {{ $isOverdue ? 'text-red-400' : 'text-gray-400' }}" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="text-xs font-semibold {{ $isOverdue ? 'text-red-500' : 'text-gray-400' }}">
                                    Deadline: {{ $tugas->deadline->format('d M Y') }}
                                </span>
                            </div>

                            {{-- Status badge --}}
                            <div class="mt-2">
                                @if($isGraded)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-emerald-100 text-emerald-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                        Nilai: {{ $submission->nilai }}
                                    </span>
                                @elseif($isSubmitted)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-blue-100 text-blue-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-blue-500 animate-pulse"></span>
                                        Menunggu Penilaian
                                    </span>
                                @elseif($isOverdue)
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-red-100 text-red-600">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span>
                                        Terlambat
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full text-[10px] font-black bg-amber-100 text-amber-700">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500"></span>
                                        Belum Dikerjakan
                                    </span>
                                @endif
                            </div>
                        </div>

                        {{-- Action button --}}
                        <a href="{{ route('siswa.tugas.show', $tugas) }}"
                           class="shrink-0 inline-flex items-center gap-1.5 px-3 py-2 rounded-xl text-xs font-bold transition-all duration-200
                               {{ $submission
                                   ? 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                                   : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm shadow-emerald-300/40' }}">
                            {{ $submission ? 'Detail' : 'Kerjakan' }}
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
