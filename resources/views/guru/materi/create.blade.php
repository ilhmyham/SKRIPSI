@extends('layouts.app')

@section('title', 'Tambah Materi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold mb-8" style="color: var(--color-primary);">Tambah Materi Baru</h1>

    <div class="card">
        <form method="POST" action="{{ route('guru.materi.store') }}" enctype="multipart/form-data" class="space-y-6">
            @csrf

            <!-- Module Selection -->
            <div>
                <label class="block text-lg font-semibold mb-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                        </svg>
                        <span>Modul Iqra</span>
                    </div>
                </label>
                <select name="modul_iqra_modul_id" required
                        class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg @error('modul_iqra_modul_id') border-red-500 @enderror">
                    <option value="">Pilih Modul</option>
                    @foreach($modules as $module)
                        <option value="{{ $module->modul_id }}" {{ old('modul_iqra_modul_id') == $module->modul_id ? 'selected' : '' }}>
                            {{ $module->nama_modul }}
                        </option>
                    @endforeach
                </select>
                @error('modul_iqra_modul_id')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Title -->
            <div>
                <label class="block text-lg font-semibold mb-2">Judul Materi</label>
                <input type="text" name="judul_materi" value="{{ old('judul_materi') }}" required
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg @error('judul_materi') border-red-500 @enderror"
                       placeholder="Contoh: Huruf Alif">
                @error('judul_materi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Hijaiyah Letter -->
            <div>
                <label class="block text-lg font-semibold mb-2">Huruf Hijaiyah</label>
                <input type="text" name="huruf_hijaiyah" value="{{ old('huruf_hijaiyah') }}"
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg @error('huruf_hijaiyah') border-red-500 @enderror"
                       placeholder="ا">
                @error('huruf_hijaiyah')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Google Drive Video ID -->
            <div>
                <label class="block text-lg font-semibold mb-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span>Google Drive Video ID</span>
                    </div>
                </label>
                <input type="text" name="file_video" value="{{ old('file_video') }}"
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg @error('file_video') border-red-500 @enderror"
                       placeholder="1ABCDefgHIj2KLmnOPqr3STuvWXyz">
                <p class="mt-2 text-sm" style="color: var(--color-text-muted);">
                    Copy ID dari URL Google Drive: https://drive.google.com/file/d/<strong>ID_VIDEO_DISINI</strong>/view
                </p>
                @error('file_video')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Image Upload -->
            <div>
                <label class="block text-lg font-semibold mb-2">
                    <div class="flex items-center gap-2">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span>Gambar Bahasa Isyarat</span>
                    </div>
                </label>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg @error('image') border-red-500 @enderror">
                <p class="mt-2 text-sm" style="color: var(--color-text-muted);">
                    Format: JPG, PNG. Maksimal 5MB
                </p>
                @error('image')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Description -->
            <div>
                <label class="block text-lg font-semibold mb-2">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="4"
                          class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg @error('deskripsi') border-red-500 @enderror"
                          placeholder="Penjelasan tambahan tentang materi ini...">{{ old('deskripsi') }}</textarea>
                @error('deskripsi')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="btn btn-primary flex-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Simpan Materi
                </button>
                <a href="{{ route('guru.materi.index') }}" class="btn btn-secondary flex-1 text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
