@extends('layouts.guru')

@section('title', 'Manajemen Materi')
@section('page-title', 'Manajemen Materi Pembelajaran')

@section('content')
    <div class="mb-6">
        <p class="text-gray-600">Pilih modul untuk mengelola materi pembelajaran</p>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($modules as $module)
            <x-card :interactive="true">
                <div class="flex items-start justify-between mb-4">
                    <div>
                        <h3 class="text-2xl font-bold mb-1" style="color: var(--color-primary);">{{ $module->nama_modul }}</h3>
                        <p class="text-sm text-gray-500">{{ $module->materi_count}} materi</p>
                    </div>
                    <div class="p-3 bg-emerald-100 rounded-full">
                        <x-icon name="book" class="w-6 h-6 text-emerald-600" />
                    </div>
                </div>

                @if($module->deskripsi)
                    <p class="text-gray-600 mb-4 line-clamp-2">{{ $module->deskripsi }}</p>
                @endif

                <a href="{{ route('guru.materi.by-module', $module) }}" 
                   class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
                    <x-icon name="eye" class="w-4 h-4" />
                    Kelola Materi
                </a>
            </x-card>
        @endforeach
    </div>

    @if($modules->isEmpty())
        <x-card>
            <div class="text-center py-12">
                <x-icon name="book" class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                <p class="text-gray-500 mb-4">Belum ada modul tersedia</p>
            </div>
        </x-card>
    @endif
@endsection
