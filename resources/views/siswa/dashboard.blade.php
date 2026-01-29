@extends('layouts.app')

@section('title', 'Dashboard Siswa')

@section('content')
<div class="bg-gray-50 min-h-screen font-sans">
    
    {{-- 1. HERO SECTION --}}
    {{-- UPDATE: pb-32 diubah menjadi pb-72 agar background hijau lebih panjang ke bawah --}}
    <div class="bg-[#00855C] pt-10 pb-32 px-4 sm:px-6 lg:px-8 rounded-b-[3rem]">
        <div class="max-w-7xl mx-auto text-center">
            
            <h1 class="text-3xl md:text-4xl font-bold text-white mb-2">
                Assalamualaikum, Siswa Belajar!
            </h1>
            <p class="text-emerald-100 text-lg mb-10">
                Siap untuk belajar mengaji hari ini?
            </p>

            {{-- Progress Card --}}
            <div class="max-w-3xl mx-auto bg-white/10 backdrop-blur-sm border border-white/20 rounded-2xl p-6 text-white text-left shadow-lg">
                <div class="flex justify-between items-end mb-2">
                    <span class="font-medium text-emerald-50">Progres Belajar Kamu</span>
                    <span class="font-bold text-2xl">{{ $overallProgress }}%</span>
                </div>
                
                <div class="w-full bg-black/20 rounded-full h-3 mb-3 overflow-hidden">
                    <div class="bg-yellow-400 h-3 rounded-full transition-all duration-500 ease-out" 
                         style="width: {{ $overallProgress }}%"></div>
                </div>

                <div class="flex justify-between text-sm text-emerald-100">
                    <span>{{ $completedModules ?? 0 }} Materi Selesai</span>
                    <span>{{ $modules->count() }} Total Materi</span>
                </div>
            </div>

        </div>
    </div>

    {{-- 2. GRID MODULE CARDS --}}
    {{-- UPDATE: -mt-32 diubah menjadi -mt-24 agar kartu tidak terlalu naik menabrak progress bar --}}
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 -mt-24 mb-20 relative z-20">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            
            @foreach($modules as $module)
                <x-module-card 
                    :modul="$module" 
                    :iteration="$loop->iteration" 
                    :href="route('siswa.materi.index', ['module' => $module->modul_id])" 
                />
            @endforeach

        </div>
    </div>

    {{-- 3. UPCOMING ASSIGNMENTS (Tugas) --}}
    @if(isset($tugasMendatang) && $tugasMendatang->count() > 0)
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mb-12">
            <div class="bg-white rounded-2xl shadow-lg border border-gray-100 p-6">
                <h2 class="text-2xl font-bold mb-6 text-gray-800">Tugas Mendatang</h2>
                <div class="space-y-4">
                    @foreach($tugasMendatang as $tugas)
                        <div class="flex flex-col sm:flex-row sm:items-center justify-between p-4 bg-emerald-50 rounded-xl hover:bg-emerald-100 transition border border-emerald-100">
                            <div class="flex-1">
                                <h3 class="font-semibold text-lg text-gray-800">{{ $tugas->judul_tugas }}</h3>
                                <div class="flex items-center gap-2 mt-2 text-sm text-gray-600">
                                    <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <span>Deadline: {{ $tugas->deadline->format('d M Y') }}</span>
                                </div>
                            </div>
                            <a href="{{ route('siswa.tugas.show', $tugas) }}" 
                               class="mt-4 sm:mt-0 inline-flex justify-center items-center px-4 py-2 bg-[#00855C] hover:bg-[#006c4b] text-white rounded-lg text-sm font-medium transition">
                                Lihat Tugas
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

</div>
@endsection