<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Belajar') — AyatIsyarat</title>
    <link rel="icon" type="image/png" href="{{ asset('images/logo.png') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <!-- Alpine.js CDN -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.1/dist/cdn.min.js"></script>
    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: #f0fdf8;
            background-image: radial-gradient(circle, rgba(5,150,105,.18) 1px, transparent 1px);
            background-size: 24px 24px;
        }
        .nav-active-pip { width: 4px; height: 4px; border-radius: 9999px; background: #059669; margin-top: 2px; }
    </style>
    @stack('styles')
</head>
<body class="min-h-screen pb-24 md:pb-0 antialiased">

    {{-- ── TOP NAVBAR ── --}}
    <nav class="sticky top-0 z-50 bg-white/90 backdrop-blur-xl border-b border-emerald-100 shadow-sm shadow-emerald-100/50"
         x-data="{ scrolled: false }" @scroll.window="scrolled = window.scrollY > 8"
         :class="scrolled ? 'shadow-md shadow-emerald-100' : ''">
        <div class="max-w-2xl md:max-w-4xl mx-auto px-4 h-14 flex items-center justify-between gap-4">

            {{-- Brand --}}
            <a href="{{ route('siswa.dashboard') }}" class="flex items-center gap-2.5 group shrink-0">
                <div class="w-12 h-12 rounded-xl bg-gradient-to-br from-slate-100 to-slate-200 flex items-center justify-center text-lg shadow-md shadow-emerald-300/50 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-300">
                    <img src="{{ asset('images/logo.png') }}" alt="Ayat Isyarat" class="h-12 w-12 object-contain">
                </div>
                <span class="text-[15px] font-black tracking-tight text-gray-900">
                    Ayat<span class="text-emerald-600">Isyarat</span>
                </span>
            </a>
                
            {{-- Desktop Nav Links (hidden on mobile) --}}
            @php
                $isDashboard = request()->routeIs('siswa.dashboard');
                $isMateri    = request()->routeIs('siswa.materi.*');
                $isKuis      = request()->routeIs('siswa.kuis.*');
                $isTugas     = request()->routeIs('siswa.tugas.*');
                $isProfil    = request()->routeIs('siswa.profile*');
            @endphp
            <div class="hidden md:flex items-center gap-1 flex-1 justify-center">
                @foreach([
                    ['route' => 'siswa.dashboard',    'label' => 'Beranda',  'active' => $isDashboard],
                    ['route' => 'siswa.materi.index', 'label' => 'Belajar',  'active' => $isMateri],
                    ['route' => 'siswa.kuis.index',   'label' => 'Kuis',     'active' => $isKuis],
                    ['route' => 'siswa.tugas.index',  'label' => 'Tugas',    'active' => $isTugas],
                    ['route' => 'siswa.profile',      'label' => 'Profil',   'active' => $isProfil],
                ] as $nav)
                    <a href="{{ route($nav['route']) }}"
                       class="px-4 py-1.5 rounded-xl text-sm font-bold transition-all duration-200
                           {{ $nav['active']
                               ? 'bg-emerald-600 text-white shadow-sm shadow-emerald-300/40'
                               : 'text-gray-500 hover:text-emerald-700 hover:bg-emerald-50' }}">
                        {{ $nav['label'] }}
                    </a>
                @endforeach
            </div>

            {{-- User --}}
            <div class="flex items-center gap-3 shrink-0">
                <div class="hidden sm:block text-right">
                    <p class="text-xs font-bold text-gray-800 leading-tight">{{ auth()->user()->name }}</p>
                    <p class="text-[10px] font-bold text-emerald-600 uppercase tracking-widest">Siswa</p>
                </div>
                <a href="{{ route('siswa.profile') }}"
                   class="w-9 h-9 rounded-full bg-gradient-to-br from-emerald-400 to-emerald-700 flex items-center justify-center text-sm font-black text-white shadow-md shadow-emerald-300/40 overflow-hidden hover:scale-110 transition-transform duration-300">
                    @if(auth()->user()->avatar)
                        @if(str_starts_with(auth()->user()->avatar, 'http'))
                            <img src="{{ auth()->user()->avatar }}" alt="avatar" class="w-full h-full object-cover">
                        @else
                            <img src="{{ asset('storage/' . auth()->user()->avatar) }}" alt="avatar" class="w-full h-full object-cover">
                        @endif
                    @else
                        {{ substr(auth()->user()->name, 0, 1) }}
                    @endif
                </a>
            </div>

        </div>
    </nav>

    {{-- ── FLASH MESSAGES ── --}}
    @if(session('success'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full"
             class="fixed top-16 right-4 z-[200] flex items-center gap-3 bg-white border-l-4 border-emerald-500 rounded-2xl px-4 py-3.5 shadow-xl shadow-emerald-100 max-w-xs">
            <svg class="w-5 h-5 text-emerald-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-bold text-emerald-900">{{ session('success') }}</p>
        </div>
    @endif
    @if(session('error'))
        <div x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 4000)"
             x-transition:leave="transition ease-in duration-300"
             x-transition:leave-start="opacity-100 translate-x-0"
             x-transition:leave-end="opacity-0 translate-x-full"
             class="fixed top-16 right-4 z-[200] flex items-center gap-3 bg-white border-l-4 border-red-500 rounded-2xl px-4 py-3.5 shadow-xl shadow-red-100 max-w-xs">
            <svg class="w-5 h-5 text-red-500 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            <p class="text-sm font-bold text-red-900">{{ session('error') }}</p>
        </div>
    @endif

    {{-- ── MAIN CONTENT ── --}}
    <main>
        @yield('content')
    </main>

    {{-- ── BOTTOM NAVIGATION (mobile only) ── --}}
    <nav class="fixed bottom-0 left-0 right-0 z-50 md:hidden bg-white/95 backdrop-blur-2xl border-t border-emerald-100 shadow-[0_-4px_24px_rgba(5,150,105,0.08)]">
        <div class="max-w-2xl mx-auto px-4 py-1.5 flex items-center">

            {{-- Beranda --}}
            @php $isDashboard = request()->routeIs('siswa.dashboard'); @endphp
            <a href="{{ route('siswa.dashboard') }}"
               class="flex-1 flex flex-col items-center gap-1 py-1.5 rounded-2xl transition-all duration-200 {{ $isDashboard ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500 hover:bg-emerald-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $isDashboard ? 'bg-emerald-600 shadow-md shadow-emerald-300/50 -translate-y-0.5' : '' }}">
                    <svg class="w-[18px] h-[18px] {{ $isDashboard ? 'text-white' : '' }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-wide">Beranda</span>
                @if($isDashboard) <div class="nav-active-pip"></div> @endif
            </a>

            {{-- Belajar --}}
            @php $isMateri = request()->routeIs('siswa.materi.*'); @endphp
            <a href="{{ route('siswa.materi.index') }}"
               class="flex-1 flex flex-col items-center gap-1 py-1.5 rounded-2xl transition-all duration-200 {{ $isMateri ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500 hover:bg-emerald-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $isMateri ? 'bg-emerald-600 shadow-md shadow-emerald-300/50 -translate-y-0.5' : '' }}">
                    <svg class="w-[18px] h-[18px] {{ $isMateri ? 'text-white' : '' }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-wide">Belajar</span>
                @if($isMateri) <div class="nav-active-pip"></div> @endif
            </a>

            {{-- Kuis --}}
            @php $isKuis = request()->routeIs('siswa.kuis.*'); @endphp
            <a href="{{ route('siswa.kuis.index') }}"
               class="flex-1 flex flex-col items-center gap-1 py-1.5 rounded-2xl transition-all duration-200 {{ $isKuis ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500 hover:bg-emerald-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $isKuis ? 'bg-emerald-600 shadow-md shadow-emerald-300/50 -translate-y-0.5' : '' }}">
                    <svg class="w-[18px] h-[18px] {{ $isKuis ? 'text-white' : '' }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-wide">Kuis</span>
                @if($isKuis) <div class="nav-active-pip"></div> @endif
            </a>

            {{-- Tugas --}}
            @php $isTugas = request()->routeIs('siswa.tugas.*'); @endphp
            <a href="{{ route('siswa.tugas.index') }}"
               class="flex-1 flex flex-col items-center gap-1 py-1.5 rounded-2xl transition-all duration-200 {{ $isTugas ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500 hover:bg-emerald-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $isTugas ? 'bg-emerald-600 shadow-md shadow-emerald-300/50 -translate-y-0.5' : '' }}">
                    <svg class="w-[18px] h-[18px] {{ $isTugas ? 'text-white' : '' }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-wide">Tugas</span>
                @if($isTugas) <div class="nav-active-pip"></div> @endif
            </a>

            {{-- Profil --}}
            @php $isProfil = request()->routeIs('siswa.profile*'); @endphp
            <a href="{{ route('siswa.profile') }}"
               class="flex-1 flex flex-col items-center gap-1 py-1.5 rounded-2xl transition-all duration-200 {{ $isProfil ? 'text-emerald-600' : 'text-gray-400 hover:text-emerald-500 hover:bg-emerald-50' }}">
                <div class="w-8 h-8 rounded-xl flex items-center justify-center transition-all duration-300 {{ $isProfil ? 'bg-emerald-600 shadow-md shadow-emerald-300/50 -translate-y-0.5' : '' }}">
                    <svg class="w-[18px] h-[18px] {{ $isProfil ? 'text-white' : '' }}" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </div>
                <span class="text-[10px] font-black uppercase tracking-wide">Profil</span>
                @if($isProfil) <div class="nav-active-pip"></div> @endif
            </a>

        </div>
    </nav>

    @stack('scripts')
</body>
</html>
