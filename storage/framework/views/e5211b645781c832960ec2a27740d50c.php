

<?php $__env->startSection('title', 'Kelola Pengguna'); ?>
<?php $__env->startSection('page-title', 'Manajemen Pengguna'); ?>

<?php $__env->startSection('content'); ?>
    <?php if (isset($component)) { $__componentOriginal163c8ba6efb795223894d5ffef5034f5 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal163c8ba6efb795223894d5ffef5034f5 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.table','data' => ['items' => $users->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'role' => $u->role->nama_role ?? '-',
            'role_id' => $u->role_id,
        ])->values(),'columns' => [
            ['key' => 'name', 'label' => 'Nama', 'class' => 'font-medium text-base'],
            ['key' => 'email', 'label' => 'Email', 'class' => 'text-gray-600'],
            [
                'key' => 'role', 
                'label' => 'Role',
                'badge' => [
                    'admin' => 'bg-blue-100 text-blue-700',
                    'guru' => 'bg-green-100 text-green-700',
                    'siswa' => 'bg-yellow-100 text-yellow-700',
                ]
            ],
        ],'filterKey' => 'role','filterOptions' => ['admin', 'guru', 'siswa'],'searchKeys' => ['name', 'email']]] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('table'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['items' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute($users->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'role' => $u->role->nama_role ?? '-',
            'role_id' => $u->role_id,
        ])->values()),'columns' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute([
            ['key' => 'name', 'label' => 'Nama', 'class' => 'font-medium text-base'],
            ['key' => 'email', 'label' => 'Email', 'class' => 'text-gray-600'],
            [
                'key' => 'role', 
                'label' => 'Role',
                'badge' => [
                    'admin' => 'bg-blue-100 text-blue-700',
                    'guru' => 'bg-green-100 text-green-700',
                    'siswa' => 'bg-yellow-100 text-yellow-700',
                ]
            ],
        ]),'filterKey' => 'role','filterOptions' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['admin', 'guru', 'siswa']),'searchKeys' => \Illuminate\View\Compilers\BladeCompiler::sanitizeComponentAttribute(['name', 'email'])]); ?>
         <?php $__env->slot('header', null, []); ?> 
                <button 
                    @click="$dispatch('open-modal-create-user')"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition"
                >
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'plus','class' => 'w-5 h-5']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'plus','class' => 'w-5 h-5']); ?>
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
                    Tambah Pengguna
                </button>
             <?php $__env->endSlot(); ?>

             <?php $__env->slot('actions', null, []); ?> 
            <button
                @click="$dispatch('set-edit-user-data', item)"
                class="text-blue-600 hover:underline text-sm font-medium"
            >
                <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'edit','class' => 'w-4 h-4 inline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'edit','class' => 'w-4 h-4 inline']); ?>
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
                Edit
            </button>

            <form method="POST" :action="`<?php echo e(url('admin/users')); ?>/${item.id}`"
                  onsubmit="return confirm('Hapus pengguna ini?')" class="inline">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit" class="text-red-600 hover:underline text-sm font-medium">
                    <?php if (isset($component)) { $__componentOriginalce262628e3a8d44dc38fd1f3965181bc = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginalce262628e3a8d44dc38fd1f3965181bc = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.icon','data' => ['name' => 'trash','class' => 'w-4 h-4 inline']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('icon'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'trash','class' => 'w-4 h-4 inline']); ?>
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
                    Hapus
                </button>
            </form>
         <?php $__env->endSlot(); ?>

         <?php $__env->slot('footer', null, []); ?> 
            <?php echo e($users->links()); ?>

         <?php $__env->endSlot(); ?>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $attributes = $__attributesOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__attributesOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal163c8ba6efb795223894d5ffef5034f5)): ?>
<?php $component = $__componentOriginal163c8ba6efb795223894d5ffef5034f5; ?>
<?php unset($__componentOriginal163c8ba6efb795223894d5ffef5034f5); ?>
<?php endif; ?>

    <!-- Create User Modal -->
    <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'create-user','title' => 'Tambah Pengguna Baru','description' => 'Buat akun baru untuk pengguna yang mengakses sistem.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'create-user','title' => 'Tambah Pengguna Baru','description' => 'Buat akun baru untuk pengguna yang mengakses sistem.']); ?>
        <form method="POST" action="<?php echo e(route('admin.users.store')); ?>" class="space-y-5">
            <?php echo csrf_field(); ?>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input 
                    type="text" 
                    name="name" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Masukkan nama lengkap"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Masukkan email"
                >
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1.5">
                    Role
                    <?php if (isset($component)) { $__componentOriginal7875b222dc4d64f17fd6d2e345da8799 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tooltip','data' => ['text' => 'Admin: kelola seluruh sistem dan pengguna. Guru: buat dan kelola materi, kuis, dan tugas. Siswa: akses pembelajaran dan kerjakan tugas.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tooltip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => 'Admin: kelola seluruh sistem dan pengguna. Guru: buat dan kelola materi, kuis, dan tugas. Siswa: akses pembelajaran dan kerjakan tugas.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $attributes = $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $component = $__componentOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
                </label>
                <select
                    name="role_id"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition appearance-none bg-white"
                >
                    <option value="">-- Pilih Role --</option>
                    <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($role->id); ?>"><?php echo e(ucfirst($role->nama_role)); ?></option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="flex gap-3 pt-4">
                <button 
                    type="button"
                    @click="$dispatch('close-modal-create-user')"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                >
                    Batal
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition"
                >
                    SIMPAN PENGGUNA
                </button>
            </div>
        </form>
     <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>

    <!-- Edit User Modal -->
    <div x-data="editUserModal()">
        <?php if (isset($component)) { $__componentOriginal9f64f32e90b9102968f2bc548315018c = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal9f64f32e90b9102968f2bc548315018c = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.modal','data' => ['name' => 'edit-user','title' => 'Edit Pengguna','description' => 'Perbarui informasi pengguna.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('modal'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['name' => 'edit-user','title' => 'Edit Pengguna','description' => 'Perbarui informasi pengguna.']); ?>
            <form :action="`<?php echo e(route('admin.users.index')); ?>/${editData.id}`" method="POST" class="space-y-5">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="name" 
                        x-model="editData.name"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    x-model="editData.email"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                >
            </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select 
                        name="role_id" 
                        x-model="editData.role_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition appearance-none bg-white"
                    >
                        <?php $__currentLoopData = $roles; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $role): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($role->id); ?>"><?php echo e(ucfirst($role->nama_role)); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1.5">
                        Reset Password (Opsional)
                        <?php if (isset($component)) { $__componentOriginal7875b222dc4d64f17fd6d2e345da8799 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.tooltip','data' => ['text' => 'Biarkan kosong jika tidak ingin mengubah password pengguna ini. Isi jika ingin mengganti password mereka.']] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('tooltip'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes(['text' => 'Biarkan kosong jika tidak ingin mengubah password pengguna ini. Isi jika ingin mengganti password mereka.']); ?>
<?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $attributes = $__attributesOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__attributesOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799)): ?>
<?php $component = $__componentOriginal7875b222dc4d64f17fd6d2e345da8799; ?>
<?php unset($__componentOriginal7875b222dc4d64f17fd6d2e345da8799); ?>
<?php endif; ?>
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-2">Password Baru</label>
                            <input 
                                type="password" 
                                name="password" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                                placeholder="Biarkan kosong jika tidak ingin mengganti"
                            >
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-2">Konfirmasi Password</label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                            >
                        </div>
                    </div>
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        @click="$dispatch('close-modal-edit-user')"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition"
                    >
                        UPDATE PENGGUNA
                    </button>
                </div>
            </form>
         <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $attributes = $__attributesOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__attributesOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal9f64f32e90b9102968f2bc548315018c)): ?>
<?php $component = $__componentOriginal9f64f32e90b9102968f2bc548315018c; ?>
<?php unset($__componentOriginal9f64f32e90b9102968f2bc548315018c); ?>
<?php endif; ?>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editUserModal', () => ({
                editData: {
                    id: '',
                    name: '',
                    email: '',
                    role: '',
                    role_id: ''
                },
                init() {
                    window.addEventListener('set-edit-user-data', (e) => {
                        const data = e.detail;
                        this.editData = {
                            id: data.id,
                            name: data.name,
                            email: data.email,
                            role: data.role,
                            role_id: data.role_id
                        };
                        this.$nextTick(() => {
                            window.dispatchEvent(new CustomEvent('open-modal-edit-user'));
                        });
                    });
                }
            }));
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('layouts.admin', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH D:\Ums\semester7\Skripsi\aplikasi\Ayatisyarat6\app6\resources\views/admin/users/index.blade.php ENDPATH**/ ?>