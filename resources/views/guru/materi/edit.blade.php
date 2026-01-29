@extends('layouts.app')

@section('title', 'Edit Materi')

@section('content')
<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <h1 class="text-4xl font-bold mb-8" style="color: var(--color-primary);">Edit Materi</h1>

    <div class="card">
        <form method="POST" action="{{ route('guru.materi.update', $materi) }}" enctype="multipart/form-data" class="space-y-6">
            @csrf
            @method('PUT')

            <!-- Module Selection -->
            <div>
                <label class="block text-lg font-semibold mb-2">Modul Iqra</label>
                <select name="modul_iqra_modul_id" required
                        class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg">
                    @foreach($modules as $module)
                        <option value="{{ $module->modul_id }}" 
                                {{ $materi->modul_iqra_modul_id == $module->modul_id ? 'selected' : '' }}>
                            {{ $module->nama_modul }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Title -->
            <div>
                <label class="block text-lg font-semibold mb-2">Judul Materi</label>
                <input type="text" name="judul_materi" value="{{ old('judul_materi', $materi->judul_materi) }}" required
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg">
            </div>

            <!-- Hijaiyah Letter -->
            <div>
                <label class="block text-lg font-semibold mb-2">Huruf Hijaiyah</label>
                <input type="text" name="huruf_hijaiyah" value="{{ old('huruf_hijaiyah', $materi->huruf_hijaiyah) }}"
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg">
            </div>

            <!-- Google Drive Video ID -->
            <div>
                <label class="block text-lg font-semibold mb-2">Google Drive Video ID</label>
                <input type="text" name="file_video" value="{{ old('file_video', $materi->file_video) }}"
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg">
                <p class="mt-2 text-sm" style="color: var(--color-text-muted);">
                    Copy ID dari URL Google Drive
                </p>
            </div>

            <!-- Current Image -->
            @if($materi->file_path)
                <div>
                    <label class="block text-lg font-semibold mb-2">Gambar Saat Ini</label>
                    <img src="{{ asset('storage/' . $materi->file_path) }}" alt="Current" class="w-48 h-48 object-cover rounded-lg">
                </div>
            @endif

            <!-- Image Upload -->
            <div>
                <label class="block text-lg font-semibold mb-2">Ganti Gambar (Opsional)</label>
                <input type="file" name="image" accept="image/*"
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg">
                <p class="mt-2 text-sm" style="color: var(--color-text-muted);">
                    Biarkan kosong jika tidak ingin mengganti gambar
                </p>
            </div>

            <!-- Description -->
            <div>
                <label class="block text-lg font-semibold mb-2">Deskripsi</label>
                <textarea name="deskripsi" rows="4"
                          class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg">{{ old('deskripsi', $materi->deskripsi) }}</textarea>
            </div>

            <!-- Submit Buttons -->
            <div class="flex gap-4">
                <button type="submit" class="btn btn-primary flex-1">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Update Materi
                </button>
                <a href="{{ route('guru.materi.index') }}" class="btn btn-secondary flex-1 text-center">
                    Batal
                </a>
            </div>
        </form>
    </div>
</div>
@endsection
