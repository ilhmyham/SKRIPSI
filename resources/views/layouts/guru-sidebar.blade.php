<!-- Guru Sidebar Navigation -->
<div class="flex h-screen" x-data="{ profileOpen: false, isSidebarOpen: localStorage.getItem('sidebarOpen') === 'true' || localStorage.getItem('sidebarOpen') === null }" x-init="$watch('isSidebarOpen', val => localStorage.setItem('sidebarOpen', val))">
    <!-- Sidebar -->
    <aside :class="isSidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-900 text-white flex flex-col sticky top-0 h-screen transition-all duration-300">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-700 flex items-center justify-center h-[89px]">
            <div class="flex items-center gap-3 w-full" :class="isSidebarOpen ? 'justify-start' : 'justify-center'">
                <div class="text-3xl shrink-0">☪️</div>
                <div x-show="isSidebarOpen" class="whitespace-nowrap transition-opacity duration-300">
                    <h1 class="text-xl font-bold">LMS Iqra</h1>
                    <p class="text-xs text-gray-400">Guru Panel</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto overflow-x-hidden">
            <a href="{{ route('guru.dashboard') }}" 
               title="Dashboard"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('guru.dashboard') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800' }}"
               :class="!isSidebarOpen && 'justify-center px-0'">
                <x-icon name="home" class="w-5 h-5 shrink-0" />
                <span x-show="isSidebarOpen" class="font-medium whitespace-nowrap transition-opacity duration-300">Dashboard</span>
            </a>

            <a href="{{ route('guru.materi.index') }}" 
               title="Manajemen Materi"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('guru.materi.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800' }}"
               :class="!isSidebarOpen && 'justify-center px-0'">
                <x-icon name="book" class="w-5 h-5 shrink-0" />
                <span x-show="isSidebarOpen" class="font-medium whitespace-nowrap transition-opacity duration-300">Manajemen Materi</span>
            </a>

            <a href="{{ route('guru.kuis.index') }}" 
               title="Manajemen Kuis"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('guru.kuis.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800' }}"
               :class="!isSidebarOpen && 'justify-center px-0'">
                <x-icon name="kuis" class="w-5 h-5 shrink-0" />
                <span x-show="isSidebarOpen" class="font-medium whitespace-nowrap transition-opacity duration-300">Manajemen Kuis</span>
            </a>

            <a href="{{ route('guru.tugas.index') }}" 
               title="Manajemen Tugas"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('guru.tugas.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800' }}"
               :class="!isSidebarOpen && 'justify-center px-0'">
                <x-icon name="clipboard-check" class="w-5 h-5 shrink-0" />
                <span x-show="isSidebarOpen" class="font-medium whitespace-nowrap transition-opacity duration-300">Manajemen Tugas</span>
            </a>

            <a href="{{ route('guru.progress.index') }}" 
               title="Monitoring Progress"
               class="hidden flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('guru.progress.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800' }}"
               :class="!isSidebarOpen && 'justify-center px-0'">
                <x-icon name="chart-bar" class="w-5 h-5 shrink-0" />
                <span x-show="isSidebarOpen" class="font-medium whitespace-nowrap transition-opacity duration-300">Monitoring Progress</span>
            </a>
        </nav>

        <!-- Footer Info -->
        <div class="p-4 border-t border-gray-700">
            <p class="text-xs text-gray-400 text-center">© {{ date('Y') }} LMS Iqra</p>
        </div>
    </aside>

    <!-- Main Content Area -->
    <div class="flex-1 flex flex-col overflow-hidden bg-gray-50">
        <!-- Top Header with Profile Dropdown -->
        <header class="bg-white shadow-sm border-b border-gray-200">
            <div class="px-6 py-4 flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <!-- Hamburger Toggle Button -->
                    <button @click="isSidebarOpen = !isSidebarOpen" class="p-2 -ml-2 rounded-lg hover:bg-gray-100 text-gray-500 transition">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    <h2 class="text-2xl font-bold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button @click="profileOpen = !profileOpen" 
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</div>
                            <div class="text-xs text-gray-500">{{ ucfirst(auth()->user()->role->nama_role) }}</div>
                        </div>
                        <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <x-icon name="chevron-down" class="w-4 h-4 text-gray-500" />
                    </button>

                    <!-- Dropdown Menu -->
                    <div x-show="profileOpen" 
                         @click.away="profileOpen = false"
                         x-transition:enter="transition ease-out duration-100"
                         x-transition:enter-start="transform opacity-0 scale-95"
                         x-transition:enter-end="transform opacity-100 scale-100"
                         x-transition:leave="transition ease-in duration-75"
                         x-transition:leave-start="transform opacity-100 scale-100"
                         x-transition:leave-end="transform opacity-0 scale-95"
                         class="absolute right-0 mt-2 w-56 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50"
                         style="display: none;">
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-sm font-semibold text-gray-700">{{ auth()->user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ auth()->user()->email }}</p>
                        </div>
                        
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 text-red-600">
                                <x-icon name="logout" class="w-5 h-5" />
                                <span class="font-medium">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6">
            @yield('content')
        </main>
    </div>
</div>
