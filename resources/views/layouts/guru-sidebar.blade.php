<!-- Guru Sidebar Navigation -->
<div class="flex h-screen" x-data="{ profileOpen: false }">
    <!-- Sidebar -->
    <aside class="w-64 bg-gray-900 text-white flex flex-col sticky top-0 h-screen">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-700">
            <div class="flex items-center gap-3">
                <div class="text-3xl">☪️</div>
                <div>
                    <h1 class="text-xl font-bold">LMS Iqra</h1>
                    <p class="text-xs text-gray-400">Guru Panel</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
            <a href="{{ route('guru.dashboard') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('guru.dashboard') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800' }}">
                <x-icon name="home" class="w-5 h-5" />
                <span class="font-medium">Dashboard</span>
            </a>

            <a href="{{ route('guru.materi.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('guru.materi.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800' }}">
                <x-icon name="book" class="w-5 h-5" />
                <span class="font-medium">Manajemen Materi</span>
            </a>

            <a href="{{ route('guru.kuis.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('guru.kuis.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800' }}">
                <x-icon name="kuis" class="w-5 h-5" />
                <span class="font-medium">Manajemen Kuis</span>
            </a>

            <a href="{{ route('guru.tugas.index') }}" 
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition {{ request()->routeIs('guru.tugas.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800' }}">
                <x-icon name="clipboard-check" class="w-5 h-5" />
                <span class="font-medium">Manajemen Tugas</span>
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
                <div>
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
