@extends('layouts.app')

@section('title', 'Pengumpulan Tugas')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <a href="{{ route('guru.tugas.index') }}" class="btn btn-secondary mb-6">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Kembali
    </a>

    <div class="card mb-8">
        <h1 class="text-4xl font-bold mb-2" style="color: var(--color-primary);">{{ $tugas->judul_tugas }}</h1>
        <p class="text-lg" style="color: var(--color-text-secondary);">Deadline: {{ $tugas->deadline->format('d M Y') }}</p>
    </div>

    <h2 class="text-2xl font-bold mb-6">Pengumpulan Siswa</h2>

    <div class="space-y-4" x-data="{ gradingId: null }">
        @foreach($pengumpulan as $submission)
            <div class="card">
                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div class="flex-1">
                        <h3 class="text-xl font-bold mb-2">{{ $submission->user->name }}</h3>
                        
                        <div class="flex flex-wrap items-center gap-4 text-sm" style="color: var(--color-text-secondary);">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Dikumpulkan: {{ $submission->tanggal_kumpul->format('d M Y H:i') }}</span>
                            </div>
                        </div>

                        <div class="mt-3">
                            <a href="{{ asset('storage/' . $submission->file_jawaban) }}" 
                               target="_blank"
                               class="inline-flex items-center gap-2 text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                                Download File
                            </a>
                        </div>
                    </div>

                    <div class="flex flex-col items-end gap-3">
                        @if($submission->nilai !== null)
                            <div class="text-4xl font-bold" style="color: var(--color-primary);">{{ $submission->nilai }}</div>
                            <button @click="gradingId = {{ $submission->pengumpulan_id }}" class="btn btn-secondary text-sm">
                                Edit Nilai
                            </button>
                        @else
                            <button @click="gradingId = {{ $submission->pengumpulan_id }}" class="btn btn-primary">
                                Beri Nilai
                            </button>
                        @endif
                    </div>
                </div>

                <!-- Grading Form (Alpine.js toggle) -->
                <div x-show="gradingId === {{ $submission->pengumpulan_id }}" x-cloak class="mt-6 pt-6 border-t border-gray-200">
                    <form method="POST" action="{{ route('guru.submissions.grade', $submission) }}" class="flex gap-4">
                        @csrf
                        <div class="flex-1">
                            <label class="block text-lg font-semibold mb-2">Nilai (0-100)</label>
                            <input type="number" name="nilai" min="0" max="100" 
                                   value="{{ $submission->nilai }}"
                                   required
                                   class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="btn btn-primary">
                                Simpan Nilai
                            </button>
                            <button type="button" @click="gradingId = null" class="btn btn-secondary">
                                Batal
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    @if($pengumpulan->isEmpty())
        <div class="text-center py-16">
            <svg class="w-24 h-24 mx-auto mb-4" style="color: var(--color-text-muted);" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <p class="text-xl" style="color: var(--color-text-secondary);">Belum ada siswa yang mengumpulkan</p>
        </div>
    @endif
</div>
@endsection
