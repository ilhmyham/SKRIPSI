@extends('layouts.guru')

@section('title', 'Detail Hasil Kuis — ' . $kuis->judul_kuis)
@section('page-title', 'Detail Hasil Kuis')

@section('content')

    {{-- Breadcrumb --}}
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-5">
        <a href="{{ route('guru.kuis.monitoring') }}" class="hover:text-gray-700 font-medium">Monitoring Kuis</a>
        <span>/</span>
        <span class="text-gray-800 font-semibold">{{ $kuis->judul_kuis }}</span>
    </div>

    {{-- Info Kuis --}}
    <div class="bg-gradient-to-br from-purple-600 to-indigo-700 rounded-2xl p-6 text-white mb-6 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
        <div class="relative z-10">
            <span class="text-xs font-bold uppercase tracking-widest text-purple-200">Kuis — {{ $kuis->module?->nama_modul }}</span>
            <h1 class="text-2xl font-black mt-1">{{ $kuis->judul_kuis }}</h1>
            <p class="text-purple-200 text-sm mt-1">{{ $totalSoal }} soal</p>
        </div>
    </div>

    {{-- Stats --}}
    <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 text-center">
            <p class="text-3xl font-black text-purple-600">{{ $hasilSiswa->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Sudah Mengerjakan</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 text-center">
            <p class="text-3xl font-black text-gray-400">{{ $totalSiswa - $hasilSiswa->count() }}</p>
            <p class="text-xs text-gray-500 mt-1">Belum Mengerjakan</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 text-center">
            <p class="text-3xl font-black text-emerald-600">{{ $avgSkor !== '-' ? $avgSkor . '%' : '-' }}</p>
            <p class="text-xs text-gray-500 mt-1">Rata-rata Skor</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-200 shadow-sm p-5 text-center">
            @php $topSkor = $hasilSiswa->max('skor') ?? '-'; @endphp
            <p class="text-3xl font-black text-blue-600">{{ $topSkor !== '-' ? $topSkor . '%' : '-' }}</p>
            <p class="text-xs text-gray-500 mt-1">Skor Tertinggi</p>
        </div>
    </div>

    {{-- Tabel Hasil --}}
    <div class="bg-white rounded-2xl border border-gray-200 shadow-sm overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <h2 class="font-semibold text-gray-800">Hasil Per Siswa</h2>
            <span class="text-xs text-gray-400">Diurutkan berdasarkan skor tertinggi</span>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="border-b border-gray-200 bg-gray-50/50">
                    <tr>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Peringkat</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Siswa</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-center">Benar</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-center">Salah</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Skor</th>
                        <th class="px-6 py-3 text-xs font-semibold uppercase tracking-wide text-gray-500 text-left">Dikerjakan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($hasilSiswa as $i => $hasil)
                        @php
                            $skor = $hasil['skor'];
                            $rank = $i + 1;
                            $medalColor = $rank === 1 ? 'text-yellow-500' : ($rank === 2 ? 'text-gray-400' : ($rank === 3 ? 'text-orange-500' : 'text-gray-300'));
                        @endphp
                        <tr class="border-b border-gray-100 hover:bg-gray-50/50 transition">
                            <td class="px-6 py-4">
                                <span class="text-lg font-black {{ $medalColor }}">
                                    {{ $rank <= 3 ? '🥇🥈🥉'[$rank - 1] : '#' . $rank }}
                                </span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center gap-3">
                                    @if($hasil['siswa']->avatar && str_starts_with($hasil['siswa']->avatar, 'http'))
                                        <img src="{{ $hasil['siswa']->avatar }}" class="w-8 h-8 rounded-full object-cover">
                                    @else
                                        <div class="w-8 h-8 bg-purple-500 rounded-full flex items-center justify-center text-white text-xs font-bold">
                                            {{ strtoupper(substr($hasil['siswa']->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <div>
                                        <p class="font-semibold text-gray-900">{{ $hasil['siswa']->name }}</p>
                                        <p class="text-xs text-gray-400">{{ $hasil['siswa']->email }}</p>
                                    </div>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-emerald-600">{{ $hasil['benar'] }}</span>
                            </td>
                            <td class="px-6 py-4 text-center">
                                <span class="font-bold text-red-500">{{ $hasil['salah'] }}</span>
                            </td>
                            <td class="px-6 py-4 min-w-[160px]">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 bg-gray-200 rounded-full overflow-hidden">
                                        <div class="h-full rounded-full {{ $skor >= 80 ? 'bg-emerald-500' : ($skor >= 60 ? 'bg-yellow-400' : 'bg-red-400') }}"
                                             style="width: {{ $skor }}%"></div>
                                    </div>
                                    <span class="text-xs font-black w-10 text-right {{ $skor >= 80 ? 'text-emerald-600' : ($skor >= 60 ? 'text-yellow-600' : 'text-red-500') }}">
                                        {{ $skor }}%
                                    </span>
                                </div>
                            </td>
                            <td class="px-6 py-4 text-xs text-gray-500">
                                {{ $hasil['dikerjakan_at'] ? \Carbon\Carbon::parse($hasil['dikerjakan_at'])->format('d M Y') : '-' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-6 py-16 text-center text-gray-500">
                                Belum ada siswa yang mengerjakan kuis ini.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

@endsection
