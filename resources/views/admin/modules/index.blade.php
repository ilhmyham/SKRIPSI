@extends('layouts.admin')

@section('title', 'Kelola Modul')
@section('page-title', 'Manajemen Modul Iqra')

@section('content')
    <x-table
        :items="$modules->map(fn($m) => [
            'id' => $m->id,
            'nama_modul' => $m->nama_modul,
            'deskripsi' => $m->deskripsi ?? '-',
            'materi_count' => $m->materials_count ?? 0,
        ])"
        :columns="[
            ['key' => 'nama_modul', 'label' => 'Nama Modul', 'class' => 'font-bold text-lg text-emerald-600'],
            ['key' => 'deskripsi', 'label' => 'Deskripsi', 'class' => 'text-gray-600'],
            ['key' => 'materi_count', 'label' => 'Jumlah Materi', 'class' => 'text-center font-semibold'],
        ]"
        :searchKeys="['nama_modul', 'deskripsi']"
    >
        <x-slot:header>
            <button 
                @click="$dispatch('open-modal-create-module')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition"
            >
                <x-icon name="plus" class="w-5 h-5" />
                Tambah Modul
            </button>
        </x-slot:header>

        <x-slot:actions>
            <button 
                @click="$dispatch('open-modal-edit-module', item)"
                class="text-blue-600 hover:underline text-sm font-medium">
                <x-icon name="edit" class="w-4 h-4 inline" />
                Edit
            </button>
            <form method="POST" :action="`{{ route('admin.modules.index') }}/${item.id}`" 
                  onsubmit="return confirm('Hapus modul ini? Semua materi terkait akan terhapus!')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline text-sm font-medium">
                    <x-icon name="trash" class="w-4 h-4 inline" />
                    Hapus
                </button>
            </form>
        </x-slot:actions>
    </x-table>

    <!-- Create Module Modal -->
    <x-modal name="create-module" title="Tambah Modul Baru" description="Buat modul Iqra baru untuk pembelajaran.">
        <form method="POST" action="{{ route('admin.modules.store') }}" class="space-y-5">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Modul</label>
                <input 
                    type="text" 
                    name="nama_modul" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Contoh: Iqra 7"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea 
                    name="deskripsi" 
                    rows="4"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition resize-none"
                    placeholder="Jelaskan modul ini..."
                ></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button 
                    type="button"
                    @click="$dispatch('close-modal-create-module')"
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Nama Modul</label>
                    <input 
                        type="text" 
                        name="nama_modul" 
                        x-model="editData.nama_modul"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea 
                        name="deskripsi" 
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
