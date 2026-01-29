@extends('layouts.app')

@section('title', 'Tugas Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold mb-8" style="color: var(--color-primary);">Tugas Saya</h1>

    <div class="space-y-4">
        @foreach($tugasList as $tugas)
            @php
                $submission = $tugas->pengumpulan->first();
                $isOverdue = $tugas->deadline < now() && !$submission;
            @endphp

            <div class="card {{ $isOverdue ? 'border-2 border-red-300' : '' }}">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-2">{{ $tugas->judul_tugas }}</h3>
                        
                        <div class="flex items-center gap-4 text-sm" style="color: var(--color-text-secondary);">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span class="{{ $isOverdue ? 'text-red-600 font-semibold' : '' }}">
                                    Deadline: {{ $tugas->deadline->format('d M Y') }}
                                </span>
                            </div>
                        </div>

                        @if($submission)
                            <div class="mt-3">
                                @if($submission->nilai !== null)
                                    <span class="inline-block px-4 py-2 rounded-full bg-green-100 text-green-800 font-semibold">
                                        Nilai: {{ $submission->nilai }}
                                    </span>
                                @else
                                    <span class="inline-block px-4 py-2 rounded-full bg-blue-100 text-blue-800">
                                        Sudah Dikumpulkan
                                    </span>
                                @endif
                            </div>
                        @elseif($isOverdue)
                            <span class="inline-block mt-3 px-4 py-2 rounded-full bg-red-100 text-red-800">
                                Terlambat
                            </span>
                        @endif
                    </div>

                    <div>
                        <a href="{{ route('siswa.tugas.show', $tugas) }}" class="btn {{ $submission ? 'btn-secondary' : 'btn-primary' }}">
                            @if($submission)
                                Lihat Detail
                            @else
                                Kerjakan
                            @endif
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    @if($tugasList->isEmpty())
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto mb-4" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-xl" style="color: var(--color-text-secondary);">Belum ada tugas</p>
        </div>
    @endif
</div>
@endsection
