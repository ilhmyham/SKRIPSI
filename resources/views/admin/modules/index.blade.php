@extends('layouts.admin')

@section('title', 'Kelola Modul')
@section('page-title', 'Manajemen Modul Iqra')

@section('content')
    <!-- Action Bar -->
    <div class="mb-6 flex items-center justify-between">
        <p class="text-gray-600">Kelola modul Iqra</p>
        
        <button 
            @click="$dispatch('open-modal-create-module')" aria-label="Tambah Modul Baru"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition focus-visible:outline-gray-900"
        >
            <x-icon name="plus" class="w-4 h-4" aria-hidden="true" />
            Tambah Modul
        </button>
    </div>

    <!-- Wrapping table with horizontal scroll wrapper for mobile -->
    <div class="overflow-x-auto pb-4">
        <div class="min-w-[800px]">
            <x-table
        :items="$modules->map(fn($m) => [
            'id' => $m->id,
            'nama_modul' => $m->nama_modul,
            'deskripsi' => $m->deskripsi ?? '-',
            'materi_count' => $m->materi_count ?? 0,
        ])"
        :columns="[
            ['key' => 'nama_modul', 'label' => 'Nama Modul', 'class' => 'font-bold text-lg text-emerald-600'],
            ['key' => 'deskripsi', 'label' => 'Deskripsi', 'class' => 'text-gray-600'],
            ['key' => 'materi_count', 'label' => 'Jumlah Materi', 'class' => 'text-center font-semibold'],
        ]"
        :searchKeys="['nama_modul', 'deskripsi']"
    >

        <x-slot:actions>
            <a 
                :href="`{{ url('admin/categories/module') }}/${item.id}`" aria-label="Lihat Kategori Modul"
                class="text-emerald-600 hover:underline text-sm font-medium inline-flex items-center gap-1 focus-visible:outline-emerald-600">
                <x-icon name="folder" class="w-4 h-4 inline" aria-hidden="true" />
                Kategori
            </a>
            <button 
                @click="$dispatch('open-modal-edit-module', item)" aria-label="Edit Modul"
                class="text-blue-600 hover:underline text-sm font-medium focus-visible:outline-blue-600">
                <x-icon name="edit" class="w-4 h-4 inline" aria-hidden="true" />
                Edit
            </button>
            <form method="POST" :action="`{{ route('admin.modules.index') }}/${item.id}`" 
                  onsubmit="return confirm('Hapus modul ini? Semua materi terkait akan terhapus!')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" aria-label="Hapus Modul" class="text-red-600 hover:underline text-sm font-medium focus-visible:outline-red-600">
                    <x-icon name="trash" class="w-4 h-4 inline" aria-hidden="true" />
                    Hapus
                </button>
            </form>
        </x-slot:actions>
    </x-table>
        </div>
    </div>

    <!-- Create Module Modal -->
    <x-modal name="create-module" title="Tambah Modul Baru" description="Buat modul Iqra baru untuk pembelajaran.">
        <form method="POST" id="form-create-module" action="{{ route('admin.modules.store') }}" class="space-y-5">
            @csrf

            <div>
                <label for="nama_modul" class="block text-sm font-medium text-gray-700 mb-2">Nama Modul</label>
                <input 
                    type="text" 
                    name="nama_modul" 
                    id="nama_modul"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Contoh: Iqra 7"
                >
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea 
                    name="deskripsi" 
                    id="deskripsi"
                    rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition resize-none"
                    placeholder="Jelaskan modul ini..."
                ></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button 
                    type="button"
                    @click="document.getElementById('form-create-module').reset(); $dispatch('close-modal-create-module')"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                >
                    Batal
                </button>
                <button 
                    type="submit"
                    class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition"
                >
                    SIMPAN MODUL
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Module Modal -->
    <div x-data="editModuleModal()" @open-modal-edit-module.window="openModal($event.detail)">
        <x-modal name="edit-module" title="Edit Modul" description="Perbarui informasi modul Iqra.">
            <form :action="`{{ route('admin.modules.index') }}/${editData.id}`" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="edit_nama_modul" class="block text-sm font-medium text-gray-700 mb-2">Nama Modul</label>
                    <input 
                        type="text" 
                        name="nama_modul" 
                        id="edit_nama_modul"
                        x-model="editData.nama_modul"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

                <div>
                    <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea 
                        name="deskripsi" 
                        id="edit_deskripsi"
                        x-model="editData.deskripsi"
                        rows="4"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition resize-none"
                    ></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        @click="$dispatch('close-modal-edit-module')"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition"
                    >
                        UPDATE MODUL
                    </button>
                </div>
            </form>
        </x-modal>
    </div>

    <script>
        function editModuleModal() {
            return {
                editData: {
                    id: '',
                    nama_modul: '',
                    deskripsi: ''
                },
                openModal(data) {
                    this.editData = {
                        id: data.id,
                        nama_modul: data.nama_modul,
                        deskripsi: data.deskripsi || ''
                    };
                }
            }
        }
    </script>
@endsection
