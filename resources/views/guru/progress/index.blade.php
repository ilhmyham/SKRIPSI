@extends('layouts.guru')

@section('title', 'Monitoring Progress Siswa')
@section('page-title', 'Monitoring Progress Siswa')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center">
                <x-icon name="users" class="w-6 h-6 text-emerald-600" />
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $siswaList->count() }}</p>
                <p class="text-sm text-gray-500">Total Siswa</p>
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
        <!-- <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-6 flex items-center gap-4">
            <div class="w-12 h-12 bg-orange-100 rounded-xl flex items-center justify-center">
                <x-icon name="chart-bar" class="w-6 h-6 text-orange-600" />
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $siswaList->count() > 0 ? round($siswaList->avg('progress_pct'), 1) : 0 }}%
                </p>
                <p class="text-sm text-gray-500">Rata-rata Progress</p>
            </div>
        </div> -->
    </div>

    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Daftar Progress Siswa</h2>
        </div>

        {{-- Mobile: Card View --}}
        <div class="md:hidden divide-y divide-gray-100">
            @forelse($siswaList as $index => $siswa)
                <div class="p-4 flex items-center justify-between gap-3">
                    <div class="flex items-center gap-3">
                        @if($siswa->avatar && str_starts_with($siswa->avatar, 'http'))
                            <img src="{{ $siswa->avatar }}" class="w-10 h-10 rounded-full object-cover shrink-0" alt="{{ $siswa->name }}">
                        @else
                            <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white text-sm font-bold shrink-0">
                                {{ strtoupper(substr($siswa->name, 0, 1)) }}
                            </div>
                        @endif
                        <div>
                            <p class="font-medium text-gray-900 text-sm">{{ $siswa->name }}</p>
                            <p class="text-xs text-gray-400">{{ $siswa->email }}</p>
                            <p class="text-xs text-gray-500 mt-0.5">
                                Selesai: <strong class="text-gray-800">{{ $siswa->completed_materi }}</strong> / {{ $totalMateri }} Materi
                            </p>
                        </div>
                    </div>
                    <a href="{{ route('guru.progress.show', $siswa) }}"
                       class="text-blue-600 hover:underline text-sm font-medium shrink-0">Detail</a>
                </div>
            @empty
                <div class="p-8 text-center text-gray-400 text-sm">Belum ada data siswa.</div>
            @endforelse
        </div>

        {{-- Desktop: Table View --}}
        <div class="hidden md:block overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">No</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Nama Siswa</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Email</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-center">Materi Selesai</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($siswaList as $index => $siswa)
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-500">{{ $index + 1 }}</td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($siswa->avatar && str_starts_with($siswa->avatar, 'http'))
                                        <img src="{{ $siswa->avatar }}" class="w-8 h-8 rounded-full object-cover" alt="{{ $siswa->name }}">
                                    @else
                                        <div class="w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($siswa->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="font-medium text-gray-900">{{ $siswa->name }}</span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-gray-600">{{ $siswa->email }}</td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-semibold text-gray-900">{{ $siswa->completed_materi }}</span>
                                <span class="text-gray-500"> / {{ $totalMateri }}</span>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('guru.progress.show', $siswa) }}"
                                   class="text-blue-600 hover:underline text-sm font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-16 text-center text-gray-500">
                                Belum ada data siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection
