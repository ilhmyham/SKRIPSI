@extends('layouts.guru')

@section('title', 'Monitoring Hasil Kuis')
@section('page-title', 'Monitoring Hasil Kuis')

@section('content')

    {{-- Stats --}}
    <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-purple-100 rounded-xl flex items-center justify-center">
                <x-icon name="clipboard-list" class="w-5 h-5 text-purple-600" />
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $kuisList->count() }}</p>
                <p class="text-xs text-gray-500">Total Kuis</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-blue-100 rounded-xl flex items-center justify-center">
                <x-icon name="users" class="w-5 h-5 text-blue-600" />
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">{{ $totalSiswa }}</p>
                <p class="text-xs text-gray-500">Total Siswa</p>
            </div>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 flex items-center gap-4">
            <div class="w-11 h-11 bg-emerald-100 rounded-xl flex items-center justify-center">
                <x-icon name="check-circle" class="w-5 h-5 text-emerald-600" />
            </div>
            <div>
                <p class="text-2xl font-bold text-gray-900">
                    {{ $kuisList->sum('total_pengerjaan') }}
                </p>
                <p class="text-xs text-gray-500">Total Pengerjaan</p>
            </div>
        </div>
    </div>

    {{-- Tabel Kuis --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100">
            <h2 class="font-semibold text-gray-800">Daftar Kuis & Rekap Pengerjaan</h2>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">No</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Judul Kuis</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Modul</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-center">Sudah Dikerjakan</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-center">Partisipasi</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($kuisList as $i => $kuis)
                        @php $pct = $totalSiswa > 0 ? round(($kuis->total_pengerjaan / $totalSiswa) * 100) : 0; @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4 text-gray-500">{{ $i + 1 }}</td>
                            <td class="px-6 py-4">
                                <p class="font-semibold text-gray-900">{{ $kuis->judul_kuis }}</p>
                                @if($kuis->deskripsi)
                                    <p class="text-xs text-gray-400 mt-0.5 line-clamp-1">{{ $kuis->deskripsi }}</p>
                                @endif
                            </td>
                            <td class="px-6 py-4">
                                <span class="inline-block px-2 py-0.5 text-xs font-bold bg-indigo-100 text-indigo-700 rounded-full">
                                    {{ $kuis->module?->nama_modul ?? '-' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-gray-900">{{ $kuis->total_pengerjaan }}</span>
                                <span class="text-gray-400"> / {{ $totalSiswa }}</span>
                            </td>
                            <td class="px-6 py-4 min-w-[160px]">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $pct >= 75 ? 'bg-emerald-500' : ($pct >= 40 ? 'bg-yellow-400' : 'bg-red-400') }}"
                                             style="width: {{ $pct }}%"></div>
                                    </div>
                                    <span class="text-xs font-semibold text-gray-600 w-8 text-right">{{ $pct }}%</span>
                                </div>
                            </td>
                            <td class="px-6 py-4">
                                <a href="{{ route('guru.kuis.monitoring.detail', $kuis) }}"
                                   class="text-sm font-medium text-blue-600 hover:underline">
                                    Lihat Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-500">Belum ada kuis.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
