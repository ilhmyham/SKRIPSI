@extends('layouts.guru')

@section('title', 'Rekap Siswa — ' . $user->name)
@section('page-title', 'Rekap Siswa')

@section('content')
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('guru.progress.index') }}" class="hover:text-emerald-600">Monitoring Progress</a>
            <span>/</span>
            <span class="font-semibold text-gray-800">{{ $user->name }}</span>
        </nav>
    </div>

    {{-- Student Info + Stats Row --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5 mb-7">

        {{-- Avatar Card --}}
        <div class="md:col-span-1 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col items-center text-center gap-3">
            @if($user->avatar && str_starts_with($user->avatar, 'http'))
                <img src="{{ $user->avatar }}" class="w-20 h-20 rounded-full object-cover shadow" alt="{{ $user->name }}">
            @else
                <div class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <p class="font-bold text-gray-900 text-base">{{ $user->name }}</p>
                <p class="text-xs text-gray-500">{{ $user->email }}</p>
            </div>
        </div>

        {{-- Stat: Materi --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center shrink-0">
                <x-icon name="check" class="w-5 h-5 text-emerald-600" />
            </div>
            <div>
                <p class="text-2xl font-black text-gray-900">{{ $completedMateri }}<span class="text-sm font-normal text-gray-400"> / {{ $totalMateri }}</span></p>
                <p class="text-xs text-gray-500">Materi Selesai</p>
            </div>
        </div>

        {{-- Stat: Kuis --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center shrink-0">
                <x-icon name="clipboard-list" class="w-5 h-5 text-purple-600" />
            </div>
            <div>
                <p class="text-2xl font-black text-gray-900">
                    {{ $hasilKuis->count() > 0 ? round($hasilKuis->avg('skor'), 1) . '%' : '-' }}
                </p>
                <p class="text-xs text-gray-500">Rata-rata Skor Kuis</p>
            </div>
        </div>

        {{-- Stat: Progress % --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                <x-icon name="chart-bar" class="w-5 h-5 text-blue-600" />
            </div>
            <div>
                <p class="text-2xl font-black text-gray-900">{{ $overallProgress }}%</p>
                <p class="text-xs text-gray-500">Progress Materi</p>
            </div>
        </div>
    </div>

    {{-- 3 Kolom: Progress Modul | Hasil Kuis | Nilai Tugas --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

        {{-- Progress per Modul --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <x-icon name="book" class="w-4 h-4 text-emerald-600" />
                <h2 class="font-semibold text-gray-800 text-sm">Progress per Modul</h2>
            </div>
            <div class="p-5 space-y-4">
                @foreach($modules as $module)
                    @php
                        $doneCount = $progressList->where('status', 'selesai')
                            ->filter(fn($p) => $p->material?->module_id === $module->id)
                            ->count();
                        $pct = $module->materials_count > 0
                            ? round(($doneCount / $module->materials_count) * 100)
                            : 0;
                    @endphp
                    <div>
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-sm font-medium text-gray-700">{{ $module->nama_modul }}</span>
                            <span class="text-xs text-gray-400">{{ $doneCount }}/{{ $module->materials_count }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full rounded-full {{ $pct >= 75 ? 'bg-emerald-500' : ($pct >= 40 ? 'bg-yellow-400' : 'bg-red-400') }}"
                                 style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Rekap Hasil Kuis --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <x-icon name="clipboard-list" class="w-4 h-4 text-purple-600" />
                <h2 class="font-semibold text-gray-800 text-sm">Hasil Kuis</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($hasilKuis as $hasil)
                    @php $s = $hasil['skor']; @endphp
                    <div class="px-5 py-3">
                        <div class="flex justify-between items-start mb-1">
                            <p class="text-sm font-medium text-gray-800 leading-tight">{{ $hasil['kuis']->judul_kuis }}</p>
                            <span class="text-sm font-black ml-3 {{ $s >= 80 ? 'text-emerald-600' : ($s >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                                {{ $s }}%
                            </span>
                        </div>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full {{ $s >= 80 ? 'bg-emerald-500' : ($s >= 60 ? 'bg-yellow-400' : 'bg-red-400') }}"
                                     style="width: {{ $s }}%"></div>
                            </div>
                            <span class="text-xs text-gray-400 whitespace-nowrap">{{ $hasil['benar'] }}/{{ $hasil['total_soal'] }} benar</span>
                        </div>
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-gray-400 text-sm">
                        Belum mengerjakan kuis.
                    </div>
                @endforelse
            </div>
        </div>

        {{-- Rekap Nilai Tugas --}}
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
            <div class="px-5 py-4 border-b border-gray-100 flex items-center gap-2">
                <x-icon name="clipboard-check" class="w-4 h-4 text-blue-600" />
                <h2 class="font-semibold text-gray-800 text-sm">Nilai Tugas</h2>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($submissions as $sub)
                    <div class="px-5 py-3">
                        <div class="flex justify-between items-start">
                            <div>
                                <p class="text-sm font-medium text-gray-800 leading-tight">{{ $sub->assignment?->judul_tugas }}</p>
                                <p class="text-xs text-gray-400 mt-0.5">{{ $sub->assignment?->module?->nama_modul }}</p>
                            </div>
                            <div class="ml-3 text-right">
                                @if(!is_null($sub->nilai))
                                    <span class="text-sm font-black {{ $sub->nilai >= 80 ? 'text-emerald-600' : ($sub->nilai >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                                        {{ $sub->nilai }}
                                    </span>
                                @else
                                    <span class="text-xs text-gray-400 italic">Belum dinilai</span>
                                @endif
                            </div>
                        </div>
                        @if($sub->catatan_guru)
                            <p class="text-xs text-gray-500 mt-1 italic">"{{ $sub->catatan_guru }}"</p>
                        @endif
                    </div>
                @empty
                    <div class="px-5 py-10 text-center text-gray-400 text-sm">
                        Belum mengumpulkan tugas.
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    {{-- Riwayat Aktivitas --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800 text-sm">Riwayat Aktivitas Materi Terkini</h2>
        </div>
        <div class="divide-y divide-gray-50">
            @forelse($progressList->take(15) as $progress)
                <div class="px-6 py-3 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-800">{{ $progress->material?->judul_materi ?? '-' }}</p>
                        <p class="text-xs text-gray-400">{{ $progress->material?->module?->nama_modul ?? '-' }}</p>
                    </div>
                    <div class="flex items-center gap-3">
                        @if($progress->status === 'selesai')
                            <span class="text-xs font-medium px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full">Selesai</span>
                        @else
                            <span class="text-xs font-medium px-2.5 py-1 bg-yellow-100 text-yellow-700 rounded-full">Dalam Proses</span>
                        @endif
                        <span class="text-xs text-gray-400">{{ $progress->updated_at->locale('id')->diffForHumans() }}</span>
                    </div>
                </div>
            @empty
                <div class="px-6 py-12 text-center text-gray-400 text-sm">
                    Siswa ini belum memiliki aktivitas pembelajaran.
                </div>
            @endforelse
        </div>
    </div>

@endsection
