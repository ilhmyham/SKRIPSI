@extends('layouts.app')

@section('title', 'Kuis')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold mb-8" style="color: var(--color-primary);">Kuis Tersedia</h1>

    <!-- Quiz Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($kuisList as $kuis)
            <div class="card card-interactive">
                <div class="mb-4">
                    <span class="inline-block px-3 py-1 text-sm rounded-full bg-green-100" style="color: var(--color-primary);">
                        {{ $kuis->materi->modulIqra->nama_modul ?? 'Modul' }}
                    </span>
                </div>

                <h3 class="font-bold text-xl mb-2">{{ $kuis->judul_kuis }}</h3>
                
                @if($kuis->deskripsi)
                    <p class="mb-4" style="color: var(--color-text-secondary);">{{ $kuis->deskripsi }}</p>
                @endif

                <div class="flex items-center gap-2 mb-4" style="color: var(--color-text-muted);">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>{{ $kuis->pertanyaan->count() }} Pertanyaan</span>
                </div>

                <a href="{{ route('siswa.kuis.show', $kuis) }}" class="btn btn-primary w-full">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Mulai Kuis
                </a>
            </div>
        @endforeach
    </div>

    @if($kuisList->isEmpty())
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto mb-4" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-xl" style="color: var(--color-text-secondary);">Belum ada kuis tersedia</p>
        </div>
    @endif
</div>
@endsection
