<!-- Admin Sidebar Navigation -->
<div class="flex h-screen" x-data="{ profileOpen: false, isSidebarOpen: localStorage.getItem('sidebarOpen') === 'true' || localStorage.getItem('sidebarOpen') === null }" x-init="$watch('isSidebarOpen', val => localStorage.setItem('sidebarOpen', val))">
    <!-- Sidebar -->
    <aside :class="isSidebarOpen ? 'w-64' : 'w-20'" class="bg-gray-900 text-white flex flex-col sticky top-0 h-screen transition-all duration-300">
        <!-- Logo -->
        <div class="p-6 border-b border-gray-700 flex items-center justify-center h-[89px]">
            <div class="flex items-center gap-3 w-full" :class="isSidebarOpen ? 'justify-start' : 'justify-center'">
                <div class="shrink-0">
                    <img src="<?php echo e(asset('images/logo.png')); ?>" alt="Ayat Isyarat" class="h-11 w-11 object-contain">
                </div>
                <div x-show="isSidebarOpen" class="whitespace-nowrap transition-opacity duration-300">
                    <h1 class="text-xl font-bold">Ayat Isyarat</h1>
                    <p class="text-xs text-gray-400">Admin Panel</p>
                </div>
            </div>
        </div>

        <!-- Navigation Menu -->
        <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto overflow-x-hidden">
            <a href="<?php echo e(route('admin.dashboard')); ?>" 
               title="Dashboard"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('admin.dashboard') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800'); ?>"
               :class="!isSidebarOpen && 'justify-center px-0'">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'home','class' => 'w-5 h-5 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'home','class' => 'w-5 h-5 shrink-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                <span x-show="isSidebarOpen" class="font-medium whitespace-nowrap transition-opacity duration-300">Dashboard</span>
            </a>

            <a href="<?php echo e(route('admin.users.index')); ?>" 
               title="Manajemen Pengguna"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('admin.users.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800'); ?>"
               :class="!isSidebarOpen && 'justify-center px-0'">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'users','class' => 'w-5 h-5 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'users','class' => 'w-5 h-5 shrink-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                <span x-show="isSidebarOpen" class="font-medium whitespace-nowrap transition-opacity duration-300">Manajemen Pengguna</span>
            </a>

            <a href="<?php echo e(route('admin.modules.index')); ?>" 
               title="Manajemen Modul"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('admin.modules.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800'); ?>"
               :class="!isSidebarOpen && 'justify-center px-0'">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'book-open','class' => 'w-5 h-5 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'book-open','class' => 'w-5 h-5 shrink-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                <span x-show="isSidebarOpen" class="font-medium whitespace-nowrap transition-opacity duration-300">Manajemen Modul</span>
            </a>

            <a href="<?php echo e(route('admin.materi.index')); ?>" 
               title="Manajemen Materi"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('admin.materi.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800'); ?>"
               :class="!isSidebarOpen && 'justify-center px-0'">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'book','class' => 'w-5 h-5 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'book','class' => 'w-5 h-5 shrink-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                <span x-show="isSidebarOpen" class="font-medium whitespace-nowrap transition-opacity duration-300">Manajemen Materi</span>
            </a>

            <a href="<?php echo e(route('admin.kuis.index')); ?>" 
               title="Manajemen Kuis"
               class="flex items-center gap-3 px-4 py-3 rounded-lg transition <?php echo e(request()->routeIs('admin.kuis.*') ? 'bg-white text-gray-900' : 'text-gray-300 hover:bg-gray-800'); ?>"
               :class="!isSidebarOpen && 'justify-center px-0'">
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'kuis','class' => 'w-5 h-5 shrink-0']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'kuis','class' => 'w-5 h-5 shrink-0']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                <span x-show="isSidebarOpen" class="font-medium whitespace-nowrap transition-opacity duration-300">Manajemen Kuis</span>
            </a>
        </nav>

        <!-- Footer Info -->
        <div class="p-4 border-t border-gray-700">
            <p class="text-xs text-gray-400 text-center">© <?php echo e(date('Y')); ?> Ayat Isyarat</p>
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
                    <h2 class="text-2xl font-bold text-gray-800"><?php echo $__env->yieldContent('page-title', 'Dashboard'); ?></h2>
                </div>

                <!-- Profile Dropdown -->
                <div class="relative">
                    <button @click="profileOpen = !profileOpen" 
                            class="flex items-center gap-3 px-4 py-2 rounded-lg hover:bg-gray-100 transition">
                        <div class="text-right">
                            <div class="text-sm font-semibold text-gray-700"><?php echo e(auth()->user()->name); ?></div>
                            <div class="text-xs text-gray-500"><?php echo e(ucfirst(auth()->user()->role->nama_role)); ?></div>
                        </div>
                        <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white font-bold">
                            <?php echo e(strtoupper(substr(auth()->user()->name, 0, 1))); ?>

                        </div>
                        <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'chevron-down','class' => 'w-4 h-4 text-gray-500']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'chevron-down','class' => 'w-4 h-4 text-gray-500']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
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
                            <p class="text-sm font-semibold text-gray-700"><?php echo e(auth()->user()->name); ?></p>
                            <p class="text-xs text-gray-500"><?php echo e(auth()->user()->email); ?></p>
                        </div>
                        
                        <form method="POST" action="<?php echo e(route('logout')); ?>">
                            <?php echo csrf_field(); ?>
                            <button type="submit" class="w-full flex items-center gap-3 px-4 py-3 text-left hover:bg-gray-50 text-red-600">
                                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'logout','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'logout','class' => 'w-5 h-5']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $attributes = $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__attributesOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
<?php if (isset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc)): ?>
<?php $component = $__componentOriginalce262628e3a8d44dc38fd1f3965181bc; ?>
<?php unset($__componentOriginalce262628e3a8d44dc38fd1f3965181bc); ?>
<?php endif; ?>
                                <span class="font-medium">Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </header>

        <!-- Main Content -->
        <main class="flex-1 overflow-y-auto p-6">
            <?php echo $__env->yieldContent('content'); ?>
        </main>
    </div>
</div>
<?php /**PATH D:\Ums\semester7\Skripsi\aplikasi\Ayatisyarat6\app6\resources\views/layouts/admin-sidebar.blade.php ENDPATH**/ ?>