{{-- Guru Sidebar Navigation — Mobile Responsive (Ringkas & Tombol Close) --}}
<div class="flex h-screen"
     x-data="{
         profileOpen: false,
         isSidebarOpen: window.innerWidth >= 1024 ? (localStorage.getItem('sidebarOpen') !== 'false') : false,
         get isMobile() { return window.innerWidth < 1024; },
         openSidebar()  { this.isSidebarOpen = true;  if (this.isMobile) document.body.style.overflow = 'hidden'; },
         closeSidebar() { this.isSidebarOpen = false; document.body.style.overflow = ''; },
         toggleSidebar(){ this.isSidebarOpen ? this.closeSidebar() : this.openSidebar(); },
     }"
     x-init="
         window.addEventListener('resize', () => {
             if (window.innerWidth >= 1024) {
                 isSidebarOpen = localStorage.getItem('sidebarOpen') !== 'false';
                 document.body.style.overflow = '';
             } else {
                 isSidebarOpen = false;
                 document.body.style.overflow = '';
             }
         });
         $watch('isSidebarOpen', val => {
             if (window.innerWidth >= 1024) localStorage.setItem('sidebarOpen', val);
         });
     ">

    {{-- ── MOBILE OVERLAY ── --}}
    <div x-show="isSidebarOpen && isMobile"
         @click="closeSidebar()"
         class="fixed inset-0 z-[55] bg-gray-900/60 lg:hidden"
         style="display: none;"
         aria-hidden="true">
    </div>
    
    {{-- ── SIDEBAR ── --}}
    <aside
        :class="isMobile
            ? (isSidebarOpen ? 'fixed z-[60] w-72 shadow-2xl translate-x-0' : 'fixed z-[60] w-72 shadow-2xl hidden')
            : (isSidebarOpen ? 'relative z-10 w-64' : 'relative z-10 w-20')"
        class="bg-gray-900 text-white flex flex-col h-screen top-0 left-0"
        aria-label="Navigasi Panel Guru"
    >
        {{-- Header Sidebar: Logo & Tombol Close (Mobile) --}}
        {{-- Header Sidebar: Logo & Tombol Close (Mobile) --}}
        <div class="p-5 border-b border-gray-700 flex items-center justify-between h-[73px]">
            
            {{-- Bagian Kiri (Logo & Nama) --}}
            <div class="flex items-center gap-3 overflow-hidden" :class="(!isSidebarOpen) ? 'justify-center w-full' : 'justify-start'">
                <div class="shrink-0">
                    <img src="{{ asset('images/logo.webp') }}" alt="Ayat Isyarat" class="h-10 w-10 object-contain" width="40" height="40">
                </div>
                <div x-show="isSidebarOpen" class="whitespace-nowrap overflow-hidden">
                    <h1 class="text-lg font-bold leading-tight">Ayat Isyarat</h1>
                    <p class="text-xs text-gray-400">Guru Panel</p>
                </div>
            </div>
            
            {{-- Tombol Silang (X) Kanan - Memakai lg:hidden agar PASTI muncul di mobile --}}
            <button @click="closeSidebar()" 
                    class="lg:hidden shrink-0 p-2 text-gray-400 hover:text-white bg-gray-800/50 hover:bg-gray-700 rounded-lg ml-2 focus:outline-none"
                    aria-label="Tutup sidebar">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                </svg>
            </button>
            
        </div>

        {{-- ── Navigation Menu ── --}}
        <nav class="flex-1 px-3 py-5 overflow-y-auto overflow-x-hidden space-y-1" aria-label="Menu Guru">
            <div x-show="isSidebarOpen || isMobile" class="px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">Umum</div>

            <a href="{{ route('guru.dashboard') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('guru.dashboard') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
               :class="(!isMobile && !isSidebarOpen) && 'justify-center px-0'">
                <x-icon name="home" class="w-5 h-5 shrink-0" aria-hidden="true" />
                <span x-show="isSidebarOpen || isMobile" class="text-sm font-medium whitespace-nowrap">Dashboard</span>
            </a>

            <div class="my-2 border-t border-gray-700/60"></div>

            <div x-show="isSidebarOpen || isMobile" class="px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">Kelola Konten</div>

            <a href="{{ route('guru.materi.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('guru.materi.*') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
               :class="(!isMobile && !isSidebarOpen) && 'justify-center px-0'">
                <x-icon name="book" class="w-5 h-5 shrink-0" aria-hidden="true" />
                <span x-show="isSidebarOpen || isMobile" class="text-sm font-medium whitespace-nowrap">Materi</span>
            </a>

            <a href="{{ route('guru.kuis.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ (request()->routeIs('guru.kuis.index') || request()->routeIs('guru.kuis.create') || request()->routeIs('guru.kuis.edit')) ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
               :class="(!isMobile && !isSidebarOpen) && 'justify-center px-0'">
                <x-icon name="kuis" class="w-5 h-5 shrink-0" aria-hidden="true" />
                <span x-show="isSidebarOpen || isMobile" class="text-sm font-medium whitespace-nowrap">Kuis</span>
            </a>

            <a href="{{ route('guru.tugas.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('guru.tugas.*') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
               :class="(!isMobile && !isSidebarOpen) && 'justify-center px-0'">
                <x-icon name="clipboard-check" class="w-5 h-5 shrink-0" aria-hidden="true" />
                <span x-show="isSidebarOpen || isMobile" class="text-sm font-medium whitespace-nowrap">Tugas</span>
            </a>

            <div class="my-2 border-t border-gray-700/60"></div>

            <div x-show="isSidebarOpen || isMobile" class="px-3 pb-1 pt-2 text-[10px] font-bold uppercase tracking-widest text-gray-500">Monitoring Siswa</div>

            <a href="{{ route('guru.progress.index') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('guru.progress.*') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
               :class="(!isMobile && !isSidebarOpen) && 'justify-center px-0'">
                <x-icon name="chart-bar" class="w-5 h-5 shrink-0" aria-hidden="true" />
                <span x-show="isSidebarOpen || isMobile" class="text-sm font-medium whitespace-nowrap">Progress Belajar</span>
            </a>

            <a href="{{ route('guru.kuis.monitoring') }}"
               class="flex items-center gap-3 px-3 py-2.5 rounded-lg {{ request()->routeIs('guru.kuis.monitoring*') ? 'bg-white text-gray-900 shadow-sm' : 'text-gray-300 hover:bg-gray-800 hover:text-white' }}"
               :class="(!isMobile && !isSidebarOpen) && 'justify-center px-0'">
                <x-icon name="clipboard-list" class="w-5 h-5 shrink-0" aria-hidden="true" />
                <span x-show="isSidebarOpen || isMobile" class="text-sm font-medium whitespace-nowrap">Hasil Kuis</span>
            </a>
        </nav>

        {{-- Footer Info --}}
        <div class="p-4 border-t border-gray-700">
            <p class="text-xs text-gray-400 text-center">© {{ date('Y') }} Ayat Isyarat</p>
        </div>
    </aside>

    {{-- ── MAIN CONTENT AREA ── --}}
    <div class="flex-1 flex flex-col overflow-hidden bg-gray-50 min-w-0 relative">       

        <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-50">
            <div class="px-4 sm:px-6 py-3 flex items-center justify-between gap-3">
                {{-- Left: Hamburger + Page Title --}}
                <div class="flex items-center gap-3 min-w-0">
                    <button @click="toggleSidebar()"
                            :aria-expanded="isSidebarOpen.toString()"
                            class="p-2 -ml-1 rounded-lg hover:bg-gray-100 text-gray-500 shrink-0 focus-visible:outline-emerald-600">
                        <svg aria-hidden="true" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-base sm:text-xl font-bold text-gray-800 truncate">@yield('page-title', 'Dashboard')</h2>
                </div>

                {{-- Right: Profile Dropdown --}}
                <div class="relative shrink-0">
                    <button @click="profileOpen = !profileOpen"
                            :aria-expanded="profileOpen.toString()"
                            class="flex items-center gap-2 sm:gap-3 px-2 sm:px-4 py-2 rounded-lg hover:bg-gray-100 focus-visible:outline-emerald-600">
                        <div class="text-right hidden sm:block">
                            <div class="text-sm font-semibold text-gray-700 leading-tight">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role->nama_role) }}</div>
                        </div>
                        <div class="w-9 h-9 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold text-sm shrink-0">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <x-icon name="chevron-down" class="w-4 h-4 text-gray-500 hidden sm:block" aria-hidden="true" />
                    </button>

                    {{-- Dropdown --}}
                    <div id="guru-profile-dropdown"
                         role="menu"
                         x-show="profileOpen"
                         @click.away="profileOpen = false"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-50"
                         style="display: none;">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500 truncate">{{ auth()->user()->email }}</p>
                        </div>
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" role="menuitem" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-red-50 text-red-600">
                                <x-icon name="logout" class="w-5 h-5" aria-hidden="true" />
                                <span class="font-medium">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        {{-- Main Content --}}
        <main id="main-content" class="flex-1 overflow-y-auto p-4 sm:p-6">
            @yield('content')
        </main>
    </div>
</div>