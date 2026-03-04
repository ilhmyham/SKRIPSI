@extends('layouts.admin')

@section('title', 'Kelola Pengguna')
@section('page-title', 'Manajemen Pengguna')

@section('content')
    <!-- Action Bar -->
    <div class="mb-6 flex items-center justify-between">
        <p class="text-gray-600">Kelola data pengguna sistem</p>
        
        <button 
            @click="$dispatch('open-modal-create-user')" aria-label="Tambah Pengguna Baru"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition focus-visible:outline-gray-900"
        >
            <x-icon name="plus" class="w-4 h-4" aria-hidden="true" />
            Tambah Pengguna
        </button>
    </div>

    <!-- Wrapping table with horizontal scroll wrapper for mobile -->
    <div class="overflow-x-auto pb-4">
        <div class="min-w-[800px]">
            <x-table
       :items="$users->map(fn($u) => [
            'id' => $u->id,
            'name' => $u->name,
            'email' => $u->email,
            'role' => $u->role->nama_role ?? '-',
            'role_id' => $u->role_id,
        ])->values()"

        :columns="[
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
        ]"
        filterKey="role"
        :filterOptions="['admin', 'guru', 'siswa']"
        :searchKeys="['name', 'email']"
    >
        <x-slot:actions>
            <button
                @click="$dispatch('set-edit-user-data', item)" aria-label="Edit Pengguna"
                class="text-blue-600 hover:underline text-sm font-medium focus-visible:outline-blue-600"
            >
                <x-icon name="edit" class="w-4 h-4 inline" aria-hidden="true" />
                Edit
            </button>

            <form method="POST" :action="`{{ url('admin/users') }}/${item.id}`"
                  onsubmit="return confirm('Hapus pengguna ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" aria-label="Hapus Pengguna" class="text-red-600 hover:underline text-sm font-medium focus-visible:outline-red-600">
                    <x-icon name="trash" class="w-4 h-4 inline" aria-hidden="true" />
                    Hapus
                </button>
            </form>
        </x-slot:actions>

       
    </x-table>
        </div>
    </div>

    <!-- Create User Modal -->
    <x-modal name="create-user" title="Tambah Pengguna Baru" description="Buat akun baru untuk pengguna yang mengakses sistem.">
        <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                <input 
                    type="text" 
                    name="name" 
                    id="name"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Masukkan nama lengkap"
                >
            </div>

            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="email"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Masukkan email"
                >
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                    <input 
                        type="password" 
                        name="password" 
                        id="password"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password</label>
                    <input 
                        type="password" 
                        name="password_confirmation" 
                        id="password_confirmation"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>
            </div>

            <div>
                <label for="role_id" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1.5">
                    Role
                    <x-tooltip text="Admin: kelola seluruh sistem dan pengguna. Guru: buat dan kelola materi, kuis, dan tugas. Siswa: akses pembelajaran dan kerjakan tugas." />
                </label>
                <select
                    name="role_id"
                    id="role_id"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition appearance-none bg-white"
                >
                    <option value="">-- Pilih Role --</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}">{{ ucfirst($role->nama_role) }}</option>
                    @endforeach
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
    </x-modal>

    <!-- Edit User Modal -->
    <div x-data="editUserModal()">
        <x-modal name="edit-user" title="Edit Pengguna" description="Perbarui informasi pengguna.">
            <form :action="`{{ route('admin.users.index') }}/${editData.id}`" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_name" class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap</label>
                    <input 
                        type="text" 
                        name="name" 
                        id="edit_name"
                        x-model="editData.name"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

            <div>
                <label for="edit_email" class="block text-sm font-medium text-gray-700 mb-2">Email</label>
                <input 
                    type="email" 
                    name="email" 
                    id="edit_email"
                    x-model="editData.email"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                >
            </div>

                <div>
                    <label for="edit_role_id" class="block text-sm font-medium text-gray-700 mb-2">Role</label>
                    <select 
                        name="role_id" 
                        id="edit_role_id"
                        x-model="editData.role_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition appearance-none bg-white"
                    >
                        @foreach($roles as $role)
                            <option value="{{ $role->id }}">{{ ucfirst($role->nama_role) }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="pt-4 border-t border-gray-200">
                    <h4 class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-1.5">
                        Reset Password (Opsional)
                        <x-tooltip text="Biarkan kosong jika tidak ingin mengubah password pengguna ini. Isi jika ingin mengganti password mereka." />
                    </h4>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label for="edit_password" class="block text-xs font-medium text-gray-600 mb-2">Password Baru</label>
                            <input 
                                type="password" 
                                name="password" 
                                id="edit_password"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                                placeholder="Biarkan kosong jika tidak ingin mengganti"
                            >
                        </div>
                        <div>
                            <label for="edit_password_confirmation" class="block text-xs font-medium text-gray-600 mb-2">Konfirmasi Password</label>
                            <input 
                                type="password" 
                                name="password_confirmation" 
                                id="edit_password_confirmation"
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
        </x-modal>
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
@endsection
