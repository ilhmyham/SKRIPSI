@php
    $currentRoute = Route::currentRouteName();

    // Helper Function
    // Kita ubah logiknya agar item aktif "melebar" (flex-grow) sementara yang lain tetap compact
    $getNavClass = function($prefix) use ($currentRoute) {
        $isActive = str_starts_with($currentRoute, $prefix);
        
        $baseClass = "flex items-center justify-center gap-2 px-3 py-2 rounded-full transition-all duration-300 ease-in-out cursor-pointer";
        
        // Jika Aktif: Background Hijau, Teks Putih, Font Bold
        if ($isActive) {
            return "$baseClass bg-emerald-900 text-white shadow-md shadow-emerald-900/20";
        }
        
        // Jika Tidak: Transparan, Teks Abu, Hover effect tipis
        return "$baseClass text-gray-400 hover:text-emerald-800 hover:bg-emerald-50/50";
    };
@endphp

<nav class="md:hidden fixed bottom-0 left-0 right-0 z-50 w-full bg-white border-t border-gray-100 shadow-[0_-4px_20px_rgba(0,0,0,0.05)] pb-[env(safe-area-inset-bottom)]">
    
    <div class="flex items-center justify-around w-full h-16 px-2 sm:px-4">

        <a href="{{ route('siswa.dashboard') }}" class="{{ $getNavClass('siswa.dashboard') }}">
            <x-icon 
                name="home" 
                class="w-6 h-6 shrink-0" 
                :fill="str_starts_with($currentRoute, 'siswa.dashboard') ? 'currentColor' : 'none'" 
            />
            @if(str_starts_with($currentRoute, 'siswa.dashboard'))
                <span class="text-sm font-medium whitespace-nowrap animate-slide-in">Home</span>
            @endif
        </a>

        <a href="{{ route('siswa.materi.index') }}" class="{{ $getNavClass('siswa.materi') }}">
            <x-icon name="book" class="w-6 h-6 shrink-0" />
            @if(str_starts_with($currentRoute, 'siswa.materi'))
                <span class="text-sm font-medium whitespace-nowrap animate-slide-in">Materi</span>
            @endif
        </a>

        <a href="{{ route('siswa.kuis.index') }}" class="{{ $getNavClass('siswa.kuis') }}">
            <x-icon name="kuis" class="w-6 h-6 shrink-0" />
            @if(str_starts_with($currentRoute, 'siswa.kuis'))
                <span class="text-sm font-medium whitespace-nowrap animate-slide-in">Kuis</span>
            @endif
        </a>

        <a href="{{ route('siswa.tugas.index') }}" class="{{ $getNavClass('siswa.tugas') }}">
            <x-icon name="clipboard" class="w-6 h-6 shrink-0" />
            @if(str_starts_with($currentRoute, 'siswa.tugas'))
                <span class="text-sm font-medium whitespace-nowrap animate-slide-in">Tugas</span>
            @endif
        </a>

        <a href="{{ route('siswa.profile') }}" class="{{ $getNavClass('siswa.profile') }}">
            <x-icon 
                name="user" 
                class="w-6 h-6 shrink-0" 
                :fill="str_starts_with($currentRoute, 'siswa.profile') ? 'currentColor' : 'none'"
            />
            @if(str_starts_with($currentRoute, 'siswa.profile'))
                <span class="text-sm font-medium whitespace-nowrap animate-slide-in">Profile</span>
            @endif
        </a>

    </div>
</nav>

<style>
    /* Animasi halus untuk teks yang muncul */
    .animate-slide-in {
        animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1) forwards;
        overflow: hidden;
    }
    
    @keyframes slideIn {
        from { 
            opacity: 0; 
            max-width: 0; 
            transform: translateX(-5px); 
        }
        to { 
            opacity: 1; 
            max-width: 100px; /* Cukup lebar untuk teks */
            transform: translateX(0); 
        }
    }
</style>