@extends('layouts.guru')

@section('title', 'Detail Progress - ' . $user->name)
@section('page-title', 'Detail Progress: ' . $user->name)

@section('content')
    {{-- Breadcrumb --}}
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('guru.progress.index') }}" class="hover:text-emerald-600">Monitoring Progress</a>
            <span>/</span>
            <span class="font-semibold">{{ $user->name }}</span>
        </nav>
    </div>

    {{-- Student Info & Stats --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
        <div class="md:col-span-1 bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex flex-col items-center text-center gap-3">
            @if($user->avatar && str_starts_with($user->avatar, 'http'))
                <img src="{{ $user->avatar }}" class="w-20 h-20 rounded-full object-cover shadow" alt="{{ $user->name }}">
            @else
                <div class="w-20 h-20 bg-emerald-500 rounded-full flex items-center justify-center text-white text-3xl font-bold shadow">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
            @endif
            <div>
                <p class="font-bold text-gray-900 text-lg">{{ $user->name }}</p>
                <p class="text-sm text-gray-500">{{ $user->email }}</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <x-icon name="check" class="w-6 h-6 text-emerald-600" />
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $completedMateri }}</p>
                <p class="text-sm text-gray-500">Materi Selesai</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                <x-icon name="book" class="w-6 h-6 text-blue-600" />
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalMateri }}</p>
                <p class="text-sm text-gray-500">Total Materi</p>
            </div>
        </div>

        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <x-icon name="chart-bar" class="w-6 h-6 text-orange-600" />
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $overallProgress }}%</p>
                <p class="text-sm text-gray-500">Total Progress</p>
            </div>
        </div>
    </div>

    {{-- Progress per Modul --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden mb-6">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Progress per Modul</h2>
        </div>
        <div class="p-6 grid grid-cols-1 md:grid-cols-2 gap-4">
            @foreach($modules as $module)
                @php
                    $doneCount = $progressList->where('status', 'selesai')
                        ->filter(fn($p) => $p->material?->module_id === $module->id)
                        ->count();
                    $pct = $module->materials_count > 0
                        ? round(($doneCount / $module->materials_count) * 100)
                        : 0;
                @endphp
                <div class="p-4 border border-gray-100 rounded-xl">
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-medium text-gray-800 text-sm">{{ $module->nama_modul }}</span>
                        <span class="text-xs text-gray-500">{{ $doneCount }}/{{ $module->materials_count }}</span>
                    </div>
                    <div class="h-2 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $pct >= 75 ? 'bg-emerald-500' : ($pct >= 40 ? 'bg-yellow-400' : 'bg-red-400') }}"
                             style="width: {{ $pct }}%"></div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>

    {{-- Recent Activity --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Riwayat Aktivitas Terkini</h2>
        </div>
        <div class="divide-y divide-gray-100">
            @forelse($progressList->take(20) as $progress)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-800 text-sm">{{ $progress->material?->judul_materi ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $progress->material?->module?->nama_modul ?? '-' }}</p>
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
                <div class="px-6 py-12 text-center text-gray-500 text-sm">
                    Siswa ini belum memiliki aktivitas pembelajaran.
                </div>
            @endforelse
        </div>
    </div>
@endsection
