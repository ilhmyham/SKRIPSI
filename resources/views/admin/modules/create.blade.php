@extends('layouts.admin')

@section('title', 'Tambah Modul')
@section('page-title', 'Tambah Modul Baru')

@section('content')
<div class="max-w-3xl mx-auto">
    <x-card title="Form Tambah Modul">
        <form method="POST" action="{{ route('admin.modules.store') }}" class="space-y-6">
            @csrf

            <x-input 
                label="Nama Modul"
                name="nama_modul"
                type="text"
                :value="old('nama_modul')"
                required
                placeholder="Contoh: Iqra 7"
            />

            <div>
                <label for="deskripsi" class="block text-lg font-semibold mb-2">Deskripsi</label>
                <textarea 
                    name="deskripsi" 
                    id="deskripsi"
                    rows="4"
                    class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:border-emerald-500 focus:ring focus:ring-emerald-200 transition"
                    placeholder="Jelaskan modul ini...">{{ old('deskripsi') }}</textarea>
            </div>

            <div class="flex gap-4 pt-4">
                <x-button type="submit" icon="check" class="flex-1 justify-center">
                    Simpan
                </x-button>
                <x-button variant="secondary" :href="route('admin.modules.index')" class="flex-1 justify-center">
                    Batal
                </x-button>
            </div>
        </form>
    </x-card>
</div>
@endsection
