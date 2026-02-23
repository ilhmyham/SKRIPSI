@extends('layouts.siswa')

@section('title', 'Beranda')

@section('content')
<div class="overflow-x-hidden">

{{-- ── HERO BANNER ── --}}
<div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-900 pt-10 pb-16 px-5 relative overflow-hidden">

    {{-- Decorative orbs --}}
    <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full -translate-y-1/2 translate-x-1/4 blur-2xl pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-56 h-56 bg-amber-400/10 rounded-full translate-y-1/2 -translate-x-1/4 blur-2xl pointer-events-none"></div>

    <div class="max-w-2xl mx-auto relative z-10">

        {{-- Greeting --}}
        <p class="text-emerald-200 text-xs font-bold uppercase tracking-widest mb-1.5">Assalamualaikum 👋</p>
        <h1 class="text-white text-3xl font-black tracking-tight mb-1.5">
            {{ explode(' ', auth()->user()->name)[0] }}
        </h1>
        <p class="text-emerald-200/80 text-sm mb-6">Siap belajar huruf hijaiyah hari ini?</p>

        {{-- Progress Card --}}
        <div class="bg-white/10 backdrop-blur-md border border-white/20 rounded-2xl p-5">
            <div class="flex items-center justify-between mb-2.5">
                <span class="text-white/80 text-sm font-bold">Progress Belajar</span>
                <span class="text-white text-xl font-black">{{ $overallProgress }}%</span>
            </div>
            <div class="h-3 bg-black/20 rounded-full overflow-hidden">
                <div class="h-full bg-gradient-to-r from-amber-400 to-amber-500 rounded-full shadow-[0_0_8px_rgba(251,191,36,0.6)] transition-all duration-700"
                     style="width: {{ $overallProgress }}%">
                </div>
            </div>
            <div class="flex justify-between mt-2.5 text-white/60 text-xs font-semibold">
                <span>{{ $completedModules ?? 0 }} materi selesai</span>
                <span>{{ $modules->count() }} total modul</span>
            </div>
        </div>

    </div>
</div>

{{-- ── MAIN CONTENT (overlaps hero) ── --}}
<div class="max-w-2xl mx-auto px-5 mt-4 pb-8 relative z-10 space-y-7">

    {{-- STAT CHIPS --}}
    <div class="grid grid-cols-3 gap-3.5">
        @foreach([
            ['num' => $modules->count(),       'label' => 'Modul'],
            ['num' => $completedModules ?? 0,  'label' => 'Selesai'],
            ['num' => $overallProgress . '%',  'label' => 'Progress'],
        ] as $stat)
            <div class="bg-white rounded-2xl p-4 text-center border-2 border-gray-200 hover:-translate-y-1 hover:border-emerald-400 hover:bg-emerald-50/30 transition-all duration-200">
                <p class="text-3xl font-black bg-gradient-to-br from-emerald-600 to-emerald-800 bg-clip-text text-transparent leading-none">
                    {{ $stat['num'] }}
                </p>
                <p class="text-[11px] font-bold text-gray-400 uppercase tracking-widest mt-1.5">{{ $stat['label'] }}</p>
            </div>
        @endforeach
    </div>

    {{-- QUICK ACTIONS --}}
    <div>
        <h2 class="text-sm font-black text-gray-800 mb-3.5 flex items-center gap-2">
            <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.6)]"></span>
            Menu Utama
        </h2>
        <div class="grid grid-cols-3 gap-3.5">
            @foreach([
                ['href' => route('siswa.materi.index'), 'emoji' => '📚', 'bg' => 'bg-emerald-50', 'name' => 'Belajar',  'sub' => 'Materi Iqra'],
                ['href' => route('siswa.kuis.index'),   'emoji' => '📝', 'bg' => 'bg-blue-50',    'name' => 'Kuis',     'sub' => 'Uji Kemampuan'],
                ['href' => route('siswa.tugas.index'),  'emoji' => '📋', 'bg' => 'bg-violet-50',  'name' => 'Tugas',    'sub' => 'Kumpulkan'],
            ] as $action)
                <a href="{{ $action['href'] }}"
                   class="bg-white rounded-2xl p-5 text-center border-2 border-gray-200 hover:-translate-y-1.5 hover:border-emerald-400 hover:bg-emerald-50/30 transition-all duration-200 group">
                    <div class="w-14 h-14 {{ $action['bg'] }} rounded-2xl flex items-center justify-center text-3xl mx-auto mb-3 group-hover:scale-110 group-hover:-rotate-6 transition-transform duration-300">
                        {{ $action['emoji'] }}
                    </div>
                    <p class="text-sm font-black text-gray-800">{{ $action['name'] }}</p>
                    <p class="text-[11px] text-gray-400 font-semibold mt-0.5">{{ $action['sub'] }}</p>
                </a>
            @endforeach
        </div>
    </div>

    {{-- MODULE GRID --}}
    <div>
        <div class="flex items-center justify-between mb-3.5">
            <h2 class="text-sm font-black text-gray-800 flex items-center gap-2">
                <span class="w-2.5 h-2.5 rounded-full bg-emerald-500 shadow-[0_0_6px_rgba(16,185,129,0.6)]"></span>
                Modul Iqra
            </h2>
            <a href="{{ route('siswa.materi.index') }}"
               class="text-xs font-bold text-emerald-600 flex items-center gap-1 hover:gap-2 transition-all duration-200">
                Semua
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                </svg>
            </a>
        </div>

        @php $arabicNums = ['١','٢','٣','٤','٥','٦','٧','٨','٩']; @endphp
        <div class="grid grid-cols-2 sm:grid-cols-3 gap-3.5">
            @foreach($modules as $module)
                @php
                    $arabicNum = $arabicNums[$loop->index] ?? ($loop->iteration);
                    $total     = $module->materials_count;
                    $done      = $module->done_count;
                    $pctMod    = round($module->progress);
                @endphp
                <a href="{{ route('siswa.materi.index', ['module' => $module->id]) }}"
                   class="bg-white rounded-2xl p-5 border-2 border-gray-200 hover:-translate-y-1.5 hover:border-emerald-400 hover:bg-emerald-50/30 transition-all duration-200 group flex flex-col relative overflow-hidden">                    

                    <p class="text-4xl font-black bg-gradient-to-br from-emerald-600 to-emerald-800 bg-clip-text text-transparent leading-none mb-2">
                        {{ $arabicNum }}
                    </p>
                    <p class="text-sm font-black text-gray-800 leading-tight">{{ $module->nama_modul }}</p>
                    <p class="text-xs text-gray-400 font-semibold mt-0.5">{{ $total }} materi</p>

                    <div class="mt-auto pt-3.5">
                        <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-400 rounded-full transition-all duration-700"
                                 style="width: {{ $pctMod }}%"></div>
                        </div>
                        <p class="text-[11px] text-gray-400 font-bold mt-1.5 text-right">{{ $done }}/{{ $total }}</p>
                    </div>
                </a>
            @endforeach
        </div>
    </div>

    {{-- TUGAS MENDATANG --}}
    @if(isset($tugasMendatang) && $tugasMendatang->count() > 0)
        <div>
            <div class="flex items-center justify-between mb-3.5">
                <h2 class="text-sm font-black text-gray-800 flex items-center gap-2">
                    <span class="w-2.5 h-2.5 rounded-full bg-amber-400 shadow-[0_0_6px_rgba(251,191,36,0.6)]"></span>
                    Tugas Mendatang
                </h2>
                <a href="{{ route('siswa.tugas.index') }}"
                   class="text-xs font-bold text-emerald-600 flex items-center gap-1 hover:gap-2 transition-all duration-200">
                    Semua
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                    </svg>
                </a>
            </div>

            <div class="space-y-3">
                @foreach($tugasMendatang as $tugas)
                    <a href="{{ route('siswa.tugas.show', $tugas) }}"
                       class="flex items-center gap-4 bg-white rounded-2xl p-4 border border-amber-100 shadow-sm hover:translate-x-1 hover:shadow-md hover:shadow-amber-100/60 transition-all duration-200 group">
                        <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-2xl shrink-0">
                            ⏰
                        </div>
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-bold text-gray-800 truncate">{{ $tugas->judul_tugas }}</p>
                            <p class="text-xs text-amber-600 font-semibold mt-0.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                {{ $tugas->deadline->format('d M Y') }}
                            </p>
                        </div>
                        <svg class="w-4 h-4 text-amber-400 group-hover:translate-x-1 transition-transform duration-200 shrink-0"
                             fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                        </svg>
                    </a>
                @endforeach
            </div>
        </div>
    @endif

</div>

</div>
@endsection