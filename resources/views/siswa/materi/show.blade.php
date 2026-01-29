@extends('layouts.app')

@section('title', $materi->judul_materi)

@section('content')
<div class="min-h-screen">
    <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Header -->
        <div class="mb-6">
            <a href="{{ route('siswa.materi.index') }}" class="btn btn-secondary mb-4">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
                </svg>
                Kembali
            </a>
            <h1 class="text-4xl font-bold" style="color: var(--color-primary);">{{ $materi->judul_materi }}</h1>
            <p class="text-lg mt-2" style="color: var(--color-text-secondary);">{{ $materi->modulIqra->nama_modul }}</p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            <!-- Video Player -->
            <div class="card">
                <h2 class="text-2xl font-bold mb-4">Video Pembelajaran</h2>
                @if($materi->file_video)
                    <div class="aspect-video bg-gray-900 rounded-lg overflow-hidden">
                        <iframe 
                            src="{{ $materi->videoEmbedUrl }}"
                            class="w-full h-full"
                            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                            allowfullscreen>
                        </iframe>
                    </div>
                @else
                    <div class="aspect-video bg-gray-100 rounded-lg flex items-center justify-center">
                        <p style="color: var(--color-text-muted);">Video belum tersedia</p>
                    </div>
                @endif
            </div>

            <!-- Sign Language Image -->
            <div class="card">
                <h2 class="text-2xl font-bold mb-4">Bahasa Isyarat</h2>
                @if($materi->file_path)
                    <div class="aspect-square bg-gray-100 rounded-lg overflow-hidden">
                        <img src="{{ asset('storage/' . $materi->file_path) }}" 
                             alt="Bahasa isyarat {{ $materi->huruf_hijaiyah }}"
                             class="w-full h-full object-contain">
                    </div>
                @else
                    <div class="aspect-square bg-gradient-to-br from-green-100 to-green-200 rounded-lg flex items-center justify-center">
                        <span class="text-9xl font-bold" style="color: var(--color-primary);">
                            {{ $materi->huruf_hijaiyah ?? '📖' }}
                        </span>
                    </div>
                @endif
            </div>
        </div>

        <!-- Description -->
        @if($materi->deskripsi)
            <div class="card mt-8">
                <h2 class="text-2xl font-bold mb-4">Deskripsi</h2>
                <p class="text-lg leading-relaxed">{{ $materi->deskripsi }}</p>
            </div>
        @endif

        <!-- Mark as Complete Button -->
        <div class="card mt-8 text-center">
            @if($progress && $progress->status_2 == 'selesai')
                <div class="py-8">
                    <svg class="w-24 h-24 mx-auto mb-4 text-green-500" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-2xl font-bold text-green-600">Materi Sudah Selesai!</p>
                    <p class="mt-2" style="color: var(--color-text-secondary);">Lanjutkan ke materi berikutnya</p>
                </div>
            @else
                <form method="POST" action="{{ route('siswa.materi.complete', $materi) }}">
                    @csrf
                    <button type="submit" class="btn btn-primary text-xl py-6 px-12">
                        <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Tandai Selesai
                    </button>
                </form>
            @endif
        </div>
    </div>
</div>
@endsection
