@extends('layouts.siswa')

@section('title', 'Hasil Kuis')

@section('content')
<div class="pb-24 min-h-screen bg-gray-50">

    {{-- ── HERO RESULT ── --}}
    @php
        $isExcellent = $score >= 80;
        $isGood      = $score >= 60 && $score < 80;
        $heroBg      = $isExcellent ? 'from-emerald-600 via-emerald-700 to-teal-800'
                     : ($isGood     ? 'from-blue-600 via-blue-700 to-indigo-800'
                                    : 'from-amber-500 via-orange-600 to-red-700');
        $ringColor   = $isExcellent ? 'ring-emerald-300'
                     : ($isGood     ? 'ring-blue-300'        : 'ring-orange-300');
        $badgeBg     = $isExcellent ? 'bg-emerald-500/30'
                     : ($isGood     ? 'bg-blue-500/30'       : 'bg-orange-500/30');
        $emoji       = $isExcellent ? '🎉' : ($isGood ? '👍' : '💪');
        $headline    = $isExcellent ? 'Luar Biasa!'  : ($isGood ? 'Bagus!'        : 'Terus Belajar!');
        $sub         = $isExcellent ? 'Kamu menguasai materi ini dengan sangat baik!'
                     : ($isGood     ? 'Hasil yang baik! Tetap semangat belajar.'
                                    : 'Jangan menyerah, ulangi materi dan coba lagi!');
    @endphp

    <div class="bg-gradient-to-br {{ $heroBg }} pt-12 pb-24 px-5 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-0 right-0 w-72 h-72 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-56 h-56 bg-white rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-lg mx-auto relative z-10 text-center">
            <div class="text-6xl mb-3">{{ $emoji }}</div>
            <h1 class="text-white text-3xl font-black mb-1">{{ $headline }}</h1>
            <p class="text-white/70 text-sm">{{ $sub }}</p>
        </div>
    </div>

    <div class="max-w-lg mx-auto px-4 -mt-14 relative z-10 space-y-4">

        {{-- Score Card --}}
        <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="p-8 text-center border-b border-gray-100">
                <div class="text-7xl font-black mb-1
                    {{ $isExcellent ? 'text-emerald-600' : ($isGood ? 'text-blue-600' : 'text-orange-500') }}">
                    {{ number_format($score, 0) }}<span class="text-4xl">%</span>
                </div>
                <p class="text-gray-400 text-sm font-semibold">Skor Akhir Kamu</p>
            </div>

            {{-- Stats Grid --}}
            <div class="grid grid-cols-3 divide-x divide-gray-100">
                <div class="py-5 text-center">
                    <div class="text-2xl font-black text-emerald-600">{{ $correctAnswers }}</div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mt-0.5">Benar</p>
                </div>
                <div class="py-5 text-center">
                    <div class="text-2xl font-black text-red-500">{{ $totalQuestions - $correctAnswers }}</div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mt-0.5">Salah</p>
                </div>
                <div class="py-5 text-center">
                    <div class="text-2xl font-black text-gray-700">{{ $totalQuestions }}</div>
                    <p class="text-xs font-bold text-gray-400 uppercase tracking-wide mt-0.5">Total</p>
                </div>
            </div>
        </div>

        {{-- Progress Visual --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm px-6 py-5">
            <div class="flex justify-between items-center mb-2">
                <span class="text-sm font-bold text-gray-600">Akurasi Jawaban</span>
                <span class="text-sm font-black {{ $isExcellent ? 'text-emerald-600' : ($isGood ? 'text-blue-600' : 'text-orange-500') }}">
                    {{ number_format($score, 0) }}%
                </span>
            </div>
            <div class="w-full bg-gray-100 rounded-full h-3">
                <div class="h-3 rounded-full transition-all duration-700
                    {{ $isExcellent ? 'bg-gradient-to-r from-emerald-500 to-teal-500'
                     : ($isGood     ? 'bg-gradient-to-r from-blue-500 to-indigo-500'
                                    : 'bg-gradient-to-r from-amber-500 to-orange-500') }}"
                     style="width: {{ $score }}%">
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="grid grid-cols-2 gap-3">
            <a href="{{ route('siswa.kuis.index') }}"
               class="flex items-center justify-center gap-2 py-4 px-4 rounded-2xl border-2 border-gray-200 bg-white text-gray-700 font-bold text-sm hover:border-gray-300 hover:shadow-sm transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                </svg>
                Kuis Lain
            </a>
            <a href="{{ route('siswa.materi.index') }}"
               class="flex items-center justify-center gap-2 py-4 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-md shadow-emerald-200 transition-all duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                Lanjut Belajar
            </a>
        </div>

    </div>
</div>
@endsection
