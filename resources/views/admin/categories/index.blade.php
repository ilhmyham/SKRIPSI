@extends('layouts.admin')

@section('title', 'Kategori ' . $module->nama_modul)
@section('page-title', 'Kelola Kategori: ' . $module->nama_modul)

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('admin.materi.index') }}" class="hover:text-emerald-600">Manajemen Materi</a>
            <span>/</span>
            <a href="{{ route('admin.materi.by-module', $module) }}" class="hover:text-emerald-600">{{ $module->nama_modul }}</a>
            <span>/</span>
            <span class="font-semibold">Kategori</span>
        </nav>
    </div>

    <x-table
        :items="$categories->map(fn($c) => [
            'id' => $c->id,
            'urutan' => $c->urutan ?? '-',
            'nama_raw' => $c->nama,
            'nama' => Str::title(str_replace('_', ' ', $c->nama)),
            'materi_count' => $c->materials_count . ' Materi',
        ])"
        :columns="[
            ['key' => 'urutan', 'label' => '#', 'class' => 'text-center text-gray-500 w-12'],
            ['key' => 'nama', 'label' => 'Nama Kategori', 'class' => 'font-bold text-base'],
            ['key' => 'materi_count', 'label' => 'Jumlah Materi', 'class' => 'text-sm text-gray-600'],
        ]"
        :searchKeys="['nama']"
    >
        <x-slot:header>
            <button 
                @click="$dispatch('open-modal-create-category')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition"
            >
                <x-icon name="plus" class="w-5 h-5" />
                Tambah Kategori
            </button>
        </x-slot:header>

        <x-slot:actions>
            <button 
                @click="$dispatch('set-edit-category-data', item)"
                class="text-blue-600 hover:underline text-sm font-medium">
                <x-icon name="edit" class="w-4 h-4 inline" />
                Edit
            </button>
            <form method="POST" :action="`{{ url('admin/categories') }}/${item.id}`" 
                  onsubmit="return confirm('Hapus kategori ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline text-sm font-medium">
                    <x-icon name="trash" class="w-4 h-4 inline" />
                    Hapus
                </button>
            </form>
        </x-slot:actions>
    </x-table>

    <!-- Create Category Modal -->
    <x-modal name="create-category" title="Tambah Kategori Baru" description="Buat kategori materi untuk modul {{ $module->nama_modul }}." maxWidth="2xl">
        <form method="POST" action="{{ route('admin.categories.store', $module) }}" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
            @csrf
            
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                <input 
                    type="text" 
                    name="nama" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Contoh: Mad 2 Harakat"
                >
                <p class="mt-1 text-xs text-gray-500">Nama kategori akan diformat secara otomatis oleh sistem.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil <span class="text-gray-400 font-normal">(Opsional)</span></label>
                <input 
                    type="number" 
                    name="urutan"
                    min="1"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Contoh: 1, 2, 3"
                >
                <p class="mt-1 text-xs text-gray-500">Isi angka urutan untuk mengatur urutan kategori di aplikasi Siswa.</p>
            </div>

            <div class="flex gap-3 pt-4">
                <button 
                    type="button"
                    @click="$dispatch('close-modal-create-category')"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                >
                    Batal
                </button>
                <button 
                    type="submit"
                    :disabled="isSubmitting"
                    class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center"
                >
                    <span x-show="!isSubmitting">SIMPAN KATEGORI</span>
                    <span x-show="isSubmitting">Menyimpan...</span>
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Category Modal -->
    <div x-data="editCategoryModal()">
        <x-modal name="edit-category" title="Edit Kategori" description="Ubah data kategori materi." maxWidth="2xl">
            <form :action="`{{ url('admin/categories') }}/${editData.id}`" method="POST" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Kategori</label>
                    <input 
                        type="text" 
                        name="nama" 
                        x-model="editData.nama_raw"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                    <p class="mt-1 text-xs text-gray-500">Nama kategori akan otomatis menyesuaikan menjadi slug di database.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutan Tampil <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input 
                        type="number" 
                        name="urutan"
                        x-model="editData.urutan"
                        min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        @click="$dispatch('close-modal-edit-category')"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="isSubmitting"
                        class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center"
                    >
                        <span x-show="!isSubmitting">UPDATE KATEGORI</span>
                        <span x-show="isSubmitting">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </x-modal>
    </div>

    <!-- Alpine Widget for Edit Modal -->
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editCategoryModal', () => ({
                editData: {
                    id: '',
                    nama_raw: '',
                    urutan: ''
                },
                init() {
                    // Listen for a separate data-setting event from the table row button
                    window.addEventListener('set-edit-category-data', (e) => {
                        const item = e.detail;
                        this.editData = {
                            id: item.id,
                            nama_raw: item.nama_raw,
                            urutan: item.urutan !== '-' ? item.urutan : ''
                        };
                        // Open the modal AFTER data is set
                        this.$nextTick(() => {
                            window.dispatchEvent(new CustomEvent('open-modal-edit-category'));
                        });
                    });
                }
            }));
        });
    </script>
@endsection
