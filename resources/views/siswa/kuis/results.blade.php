@extends('layouts.siswa')

@section('title', 'Hasil Kuis')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-2xl w-full">
        <div class="card text-center">
            <!-- Score Display -->
            <div class="mb-8">
                @php
                    $score = session('score', 0);
                    $correct = session('correct', 0);
                    $total = session('total', 0);
                @endphp

                @if($score >= 80)
                    <!-- Excellent -->
                    <div class="text-8xl mb-4">🎉</div>
                    <h1 class="text-5xl font-bold mb-4 text-green-600">Luar Biasa!</h1>
                @elseif($score >= 60)
                    <!-- Good -->
                    <div class="text-8xl mb-4">👍</div>
                    <h1 class="text-5xl font-bold mb-4 text-blue-600">Bagus!</h1>
                @else
                    <!-- Keep trying -->
                    <div class="text-8xl mb-4">💪</div>
                    <h1 class="text-5xl font-bold mb-4 text-yellow-600">Terus Belajar!</h1>
                @endif

                <div class="text-7xl font-bold mb-2" style="color: var(--color-primary);">
                    {{ number_format($score, 0) }}%
                </div>
                <p class="text-2xl" style="color: var(--color-text-secondary);">
                    {{ $correct }} dari {{ $total }} benar
                </p>
            </div>

            <!-- Score Breakdown -->
            <div class="grid grid-cols-2 gap-6 mb-8">
                <div class="p-6 bg-green-50 rounded-lg">
                    <div class="text-4xl font-bold text-green-600 mb-2">{{ $correct }}</div>
                    <p class="text-lg text-green-700">Benar</p>
                </div>
                <div class="p-6 bg-red-50 rounded-lg">
                    <div class="text-4xl font-bold text-red-600 mb-2">{{ $total - $correct }}</div>
                    <p class="text-lg text-red-700">Salah</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex gap-4">
                <a href="{{ route('siswa.kuis.index') }}" class="btn btn-secondary flex-1 text-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Lihat Kuis Lain
                </a>
                <a href="{{ route('siswa.materi.index') }}" class="btn btn-primary flex-1 text-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                    Lanjut Belajar
                </a>
            </div>
        </div>
    </div>
</div>
@endsection
