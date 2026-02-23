@extends('layouts.admin')

@section('title', 'Kuis ' . $module->nama_modul)
@section('page-title', 'Kuis ' . $module->nama_modul)

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6 flex items-center justify-between">
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.kuis.index') }}" class="hover:text-emerald-600">Manajemen Kuis</a>
            <span>/</span>
            <span class="font-semibold">{{ $module->nama_modul }}</span>
        </nav>

        <a href="{{ route('admin.kuis.create', ['module_id' => $module->id]) }}" 
           class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition">
            <x-icon name="plus" class="w-5 h-5" />
            Tambah Kuis
        </a>
    </div>

    @if($kuisList->isEmpty())
        <x-card>
            <div class="text-center py-12">
                <x-icon name="kuis" class="w-16 h-16 mx-auto mb-4 text-gray-300" />
                <p class="text-gray-500 mb-4">Belum ada kuis untuk modul ini</p>
                <a href="{{ route('admin.kuis.create', ['module_id' => $module->id]) }}" 
                   class="text-emerald-600 hover:underline">
                    Buat kuis pertama
                </a>
            </div>
        </x-card>
    @else
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($kuisList as $kuis)
                <x-card :interactive="true">
                    <div class="flex items-start justify-between mb-3">
                        <h3 class="text-lg font-bold flex-1" style="color: var(--color-primary);">{{ $kuis->judul_kuis }}</h3>
                        <span class="ml-2 px-3 py-1 bg-purple-100 text-purple-700 text-xs font-semibold rounded-full">
                            {{ $kuis->pertanyaan_count }} soal
                        </span>
                    </div>

                    @if($kuis->deskripsi)
                        <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $kuis->deskripsi }}</p>
                    @endif

                    <div class="flex gap-2 pt-3 border-t border-gray-100">
                        <a href="{{ route('admin.kuis.edit', $kuis) }}" 
                           class="flex-1 inline-flex items-center justify-center gap-1 px-3 py-2 bg-blue-50 text-blue-600 rounded-lg hover:bg-blue-100 transition text-sm font-medium">
                            <x-icon name="edit" class="w-4 h-4" />
                            Edit
                        </a>
                        <form method="POST" action="{{ route('admin.kuis.destroy', $kuis) }}" 
                              onsubmit="return confirm('Hapus kuis ini?')" class="flex-1">
                            @csrf
                            @method('DELETE')
                            <button type="submit" 
                                    class="w-full inline-flex items-center justify-center gap-1 px-3 py-2 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition text-sm font-medium">
                                <x-icon name="trash" class="w-4 h-4" />
                                Hapus
                            </button>
                        </form>
                    </div>
                </x-card>
            @endforeach
        </div>
    @endif
@endsection
