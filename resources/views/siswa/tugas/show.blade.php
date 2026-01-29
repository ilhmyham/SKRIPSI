@extends('layouts.app')

@section('title', $tugas->judul_tugas)

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('siswa.tugas.index') }}" class="btn btn-secondary mb-6">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <!-- Assignment Details -->
    <div class="card mb-6">
        <h1 class="text-4xl font-bold mb-4" style="color: var(--color-primary);">{{ $tugas->judul_tugas }}</h1>
        
        <div class="flex items-center gap-3 mb-6">
            <svg class="w-6 h-6" style="color: var(--color-text-secondary);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span class="text-lg">
                <strong>Deadline:</strong> {{ $tugas->deadline->format('d M Y') }}
                @if($tugas->deadline < now())
                    <span class="ml-2 px-3 py-1 bg-red-100 text-red-700 rounded-full text-sm">Terlambat</span>
                @endif
            </span>
        </div>

        @if($tugas->deskripsi_tugas)
            <div class="mb-6">
                <h3 class="text-xl font-semibold mb-2">Deskripsi Tugas</h3>
                <p class="text-lg leading-relaxed" style="color: var(--color-text-secondary);">{{ $tugas->deskripsi_tugas }}</p>
            </div>
        @endif
    </div>

    @if($pengumpulan)
        <!-- Submission Info -->
        <div class="card">
            <h2 class="text-2xl font-bold mb-4">Status Pengumpulan</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm font-semibold" style="color: var(--color-text-secondary);">File yang Dikumpulkan:</p>
                    <a href="{{ asset('storage/' . $pengumpulan->file_jawaban) }}" 
                       target="_blank"
                       class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        Download File
                    </a>
                </div>

                <div>
                    <p class="text-sm font-semibold" style="color: var(--color-text-secondary);">Dikumpulkan pada:</p>
                    <p class="text-lg">{{ $pengumpulan->tanggal_kumpul->format('d M Y H:i') }}</p>
                </div>

                @if($pengumpulan->nilai !== null)
                    <div>
                        <p class="text-sm font-semibold" style="color: var(--color-text-secondary)">Nilai:</p>
                        <div class="text-5xl font-bold mt-2" style="color: var(--color-primary);">{{ $pengumpulan->nilai }}</div>
                    </div>
                @else
                    <div class="p-4 bg-blue-50 rounded-lg">
                        <p class="text-blue-800">Tugas sedang dinilai oleh guru</p>
                    </div>
                @endif
            </div>
        </div>
    @else
        <!-- Upload Form -->
        <div class="card">
            <h2 class="text-2xl font-bold mb-6">Kumpulkan Tugas</h2>
            
            <form method="POST" action="{{ route('siswa.tugas.submit', $tugas) }}" enctype="multipart/form-data" class="space-y-6">
                @csrf

                <div>
                    <label class="block text-lg font-semibold mb-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"/>
                            </svg>
                            <span>Upload File Tugas</span>
                        </div>
                    </label>
                    <input type="file" name="file" required accept=".pdf,.doc,.docx,.jpg,.jpeg,.png"
                           class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg @error('file') border-red-500 @enderror">
                    <p class="mt-2 text-sm" style="color: var(--color-text-muted);">
                        Format: PDF, DOC, DOCX, JPG, PNG. Maksimal 10MB
                    </p>
                    @error('file')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button type="submit" class="btn btn-primary w-full text-xl py-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Kumpulkan Tugas
                </button>
            </form>
        </div>
    @endif
</div>
@endsection
