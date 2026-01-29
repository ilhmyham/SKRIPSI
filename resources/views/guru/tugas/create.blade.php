@extends('layouts.app')

@section('title', 'Buat Tugas')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold mb-8" style="color: var(--color-primary);">Buat Tugas Baru</h1>

    <div class="card">
        <form method="POST" action="{{ route('guru.tugas.store') }}" class="space-y-6">
            @csrf

            <div>
                <label class="block text-lg font-semibold mb-2">Judul Tugas</label>
                <input type="text" name="judul_tugas" value="{{ old('judul_tugas') }}" required
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg @error('judul_tugas') border-red-500 @enderror"
                       placeholder="Contoh: Baca Surat Al-Fatihah">
                @error('judul_tugas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-lg font-semibold mb-2">Deskripsi Tugas</label>
                <textarea name="deskripsi_tugas" rows="5"
                          class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg @error('deskripsi_tugas') border-red-500 @enderror"
                          placeholder="Jelaskan detail tugas...">{{ old('deskripsi_tugas') }}</textarea>
                @error('deskripsi_tugas')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-lg font-semibold mb-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Deadline</span>
                    </div>
                </label>
                <input type="date" name="deadline" value="{{ old('deadline') }}" required
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg @error('deadline') border-red-500 @enderror">
                @error('deadline')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex gap-4">
                <button type="submit" class="btn btn-primary flex-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Buat Tugas
                </button>
                <a href="{{ route('guru.tugas.index') }}" class="btn btn-secondary flex-1 text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
