<nav class="bg-emerald-700 text-white shadow-md border-b border-emerald-600 sticky top-0 z-50">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
            
            <div class="flex items-center flex-shrink-0">
                <a href="#" class="flex items-center gap-2 group">
                    <div class="bg-white/10 p-1 rounded-lg group-hover:bg-white/20 transition-all duration-300">
                        <img src="{{ asset('images/logo.png') }}" alt="Ayat Isyarat" class="h-10 w-10 object-contain">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-xl font-bold tracking-tight text-white leading-none">Ayat Isyarat</span>
                        <span class="text-[10px] text-emerald-200 font-medium tracking-wide uppercase">Learning Platform</span>
                    </div>
                </a>
            </div>

            @if(auth()->user()->isSiswa())
                <div class="hidden md:flex items-center gap-1"> 
                    <div class="flex items-center gap-1 bg-emerald-800/40 p-1.5 rounded-full border border-emerald-600/30">
                        
                        <a href="{{ route('siswa.dashboard') }}" 
                           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ request()->routeIs('siswa.dashboard') ? 'bg-white text-emerald-800 shadow-md transform scale-105' : 'text-emerald-50 hover:bg-emerald-700/50 hover:text-white' }}">
                            <x-icon name="home" class="w-4 h-4" />
                            <span>Beranda</span>
                        </a>

                        <a href="{{ route('siswa.materi.index') }}" 
                           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ request()->routeIs('siswa.materi.*') ? 'bg-white text-emerald-800 shadow-md transform scale-105' : 'text-emerald-50 hover:bg-emerald-700/50 hover:text-white' }}">
                            <x-icon name="book" class="w-4 h-4" />
                            <span>Belajar</span>
                        </a>

                        <a href="{{ route('siswa.kuis.index') }}" 
                           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ request()->routeIs('siswa.kuis.*') ? 'bg-white text-emerald-800 shadow-md transform scale-105' : 'text-emerald-50 hover:bg-emerald-700/50 hover:text-white' }}">
                            <x-icon name="kuis" class="w-4 h-4" />
                            <span>Kuis</span>
                        </a>

                        <a href="{{ route('siswa.profile') }}" 
                           class="flex items-center gap-2 px-4 py-2 rounded-full text-sm font-medium transition-all duration-300 {{ request()->routeIs('siswa.profile*') ? 'bg-white text-emerald-800 shadow-md transform scale-105' : 'text-emerald-50 hover:bg-emerald-700/50 hover:text-white' }}">
                            <x-icon name="users" class="w-4 h-4" />
                            <span>Profile</span>
                        </a>

                    </div>
                </div>
            @endif

            <div class="flex items-center gap-4">
                
                <div class="hidden md:flex items-center gap-3 pl-4 border-l border-emerald-600/50">
                    <div class="text-right">
                        <div class="text-sm font-semibold text-white leading-tight">{{ auth()->user()->name }}</div>
                        <div class="text-[10px] uppercase tracking-wider text-emerald-200 font-medium">{{ auth()->user()->role->nama_role }}</div>
                    </div>
                    <div class="h-9 w-9 rounded-full bg-emerald-800 border-2 border-emerald-400 flex items-center justify-center text-sm font-bold shadow-sm">
                        {{ substr(auth()->user()->name, 0, 1) }}
                    </div>
                </div>

                <form method="POST" action="{{ route('logout') }}" class="flex">
                    @csrf
                    <button type="submit" class="group flex items-center justify-center w-9 h-9 rounded-full bg-white/10 text-white hover:bg-red-500 hover:text-white transition-all duration-200 backdrop-blur-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 focus:ring-offset-emerald-700" title="Keluar Aplikasi">
                        <x-icon name="logout" class="w-5 h-5 transform group-hover:translate-x-0.5 transition-transform" />
                    </button>
                </form>

            </div>

        </div>
    </div>
</nav>