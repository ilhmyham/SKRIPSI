@php
    $currentRoute = Route::currentRouteName();

    // Helper untuk mempersingkat logika class
    // Jika Aktif: Background Hijau Gelap, Teks Putih, Lebar.
    // Jika Tidak: Transparan, Icon Abu, Sempit.
    $getNavClass = function($prefix) use ($currentRoute) {
        $isActive = str_starts_with($currentRoute, $prefix);
        
        return $isActive 
            ? "bg-emerald-900 text-white px-5 py-3 rounded-full flex items-center gap-2 transition-all duration-500 ease-out shadow-lg shadow-emerald-900/20"
            : "text-gray-400 hover:text-emerald-800 p-3 rounded-full transition-all duration-300 ease-in-out hover:bg-gray-50";
    };
@endphp

<!-- Bottom Navigation - Only visible on Mobile -->
<nav class="md:hidden fixed bottom-8 left-1/2 transform -translate-x-1/2 z-50 w-auto max-w-[95vw]">
    
    <div class="bg-white/90 backdrop-blur-md border border-gray-200/60 rounded-full shadow-[0_8px_30px_rgb(0,0,0,0.12)] p-1.5 flex items-center gap-1 sm:gap-2">

        <a href="{{ route('siswa.dashboard') }}" 
           class="{{ $getNavClass('siswa.dashboard') }}">
            
            <x-icon 
                name="home" 
                class="w-5 h-5" 
                :fill="str_starts_with($currentRoute, 'siswa.dashboard') ? 'currentColor' : 'none'" 
            />
            
            @if(str_starts_with($currentRoute, 'siswa.dashboard'))
                <span class="text-sm font-semibold whitespace-nowrap animate-fade-in">Home</span>
            @endif
        </a>

        <a href="{{ route('siswa.materi.index') }}" 
           class="{{ $getNavClass('siswa.materi') }}">
            
            <x-icon name="book" class="w-6 h-6" />

            @if(str_starts_with($currentRoute, 'siswa.materi'))
                <span class="text-sm font-semibold whitespace-nowrap animate-fade-in">Materi</span>
            @endif
        </a>

        <a href="{{ route('siswa.kuis.index') }}" 
           class="{{ $getNavClass('siswa.kuis') }}">
            
            <x-icon name="kuis" class="w-6 h-6" />

            @if(str_starts_with($currentRoute, 'siswa.kuis'))
                <span class="text-sm font-semibold whitespace-nowrap animate-fade-in">Kuis</span>
            @endif
        </a>

        <a href="{{ route('siswa.tugas.index') }}" 
           class="{{ $getNavClass('siswa.tugas') }}">
            
            <x-icon name="clipboard" class="w-6 h-6" />

            @if(str_starts_with($currentRoute, 'siswa.tugas'))
                <span class="text-sm font-semibold whitespace-nowrap animate-fade-in">Tugas</span>
            @endif
        </a>

        <a href="{{ route('siswa.profile') }}" 
           class="{{ $getNavClass('siswa.profile') }}">
            
            <x-icon 
                name="user" 
                class="w-6 h-6" 
                :fill="str_starts_with($currentRoute, 'siswa.profile') ? 'currentColor' : 'none'"
                :stroke="str_starts_with($currentRoute, 'siswa.profile') ? 'currentColor' : 'currentColor'"
            />

            @if(str_starts_with($currentRoute, 'siswa.profile'))
                <span class="text-sm font-semibold whitespace-nowrap animate-fade-in">Profile</span>
            @endif
        </a>
    </div>
</nav>

<style>
    .animate-fade-in {
        animation: fadeIn 0.4s ease-in-out forwards;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateX(-5px); }
        to { opacity: 1; transform: translateX(0); }
    }
</style>