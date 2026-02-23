@extends('layouts.guru')

@section('title', 'Kelola Tugas')
@section('page-title', 'Manajemen Tugas')

@section('content')
    <x-table
       :items="$tugasList->map(fn($t) => [
            'id' => $t->id,
            'module_id' => $t->module_id,
            'judul_tugas' => $t->judul_tugas,
            'deskripsi_tugas' => $t->deskripsi_tugas ?? '-',
            'deadline' => $t->deadline->format('d M Y'),
            'deadline_raw' => $t->deadline->format('Y-m-d'),
            'pengumpulan_count' => $t->pengumpulan_count ?? 0,
        ])"

        :columns="[
            ['key' => 'judul_tugas', 'label' => 'Judul Tugas', 'class' => 'font-bold text-base'],
            ['key' => 'deadline', 'label' => 'Deadline', 'class' => 'text-gray-600'],
            ['key' => 'pengumpulan_count', 'label' => 'Pengumpulan', 'class' => 'text-center'],
        ]"
        :searchKeys="['judul_tugas']"
    >
        <x-slot:header>
                <button 
                    @click="$dispatch('open-modal-create-tugas')"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition"
                >
                    <x-icon name="plus" class="w-5 h-5" />
                    Tambah Tugas
                </button>
            </x-slot:header>

            <x-slot:actions>
            <a :href="`{{ route('guru.tugas.index') }}/${item.id}/submissions`"
               class="text-blue-600 hover:underline text-sm font-medium">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Pengumpulan
            </a>

            <button
                @click="$dispatch('open-modal-edit-tugas', item)"
                class="text-green-600 hover:underline text-sm font-medium"
            >
                <x-icon name="edit" class="w-4 h-4 inline" />
                Edit
            </button>

            <form method="POST" :action="`{{ route('guru.tugas.index') }}/${item.id}`" 
                  onsubmit="return confirm('Hapus tugas ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline text-sm font-medium">
                    <x-icon name="trash" class="w-4 h-4 inline" />
                    Hapus
                </button>
            </form>
        </x-slot:actions>

        <x-slot:footer>
            {{ $tugasList->links() }}
        </x-slot:footer>
    </x-table>

    <!-- Create Tugas Modal -->
    <x-modal name="create-tugas" title="Tambah Tugas Baru" description="Buat tugas baru untuk siswa." maxWidth="2xl">
        <form method="POST" action="{{ route('guru.tugas.store') }}" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
            @csrf

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Modul Pembelajaran</label>
                <select name="module_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                    <option value="">-- Pilih Modul --</option>
                    @foreach($modules as $module)
                        <option value="{{ $module->id }}">{{ $module->nama_modul }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Judul Tugas</label>
                <input 
                    type="text" 
                    name="judul_tugas" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Masukkan judul tugas"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea 
                    name="deskripsi_tugas" 
                    rows="4"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition resize-none"
                    placeholder="Jelaskan detail tugas..."
                ></textarea>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                <input 
                    type="date" 
                    name="deadline" 
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                >
            </div>

            <div class="flex gap-3 pt-4">
                <button 
                    type="button"
                    @click="$dispatch('close-modal-create-tugas')"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                >
                    Batal
                </button>
                <button 
                    type="submit"
                    :disabled="isSubmitting"
                    class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center"
                >
                    <span x-show="!isSubmitting">BUAT TUGAS</span>
                    <span x-show="isSubmitting">Membuat...</span>
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Tugas Modal -->
    <div x-data="editTugasModal()" @open-modal-edit-tugas.window="openModal($event.detail)">
        <x-modal name="edit-tugas" title="Edit Tugas" description="Perbarui informasi tugas." maxWidth="2xl">
            <form :action="`{{ route('guru.tugas.index') }}/${editData.id}`" method="POST" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf
                @method('PUT')

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Modul Pembelajaran</label>
                    <select name="module_id" x-model="editData.module_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                        <option value="">-- Pilih Modul --</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->nama_modul }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul Tugas</label>
                    <input 
                        type="text" 
                        name="judul_tugas" 
                        x-model="editData.judul_tugas"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea 
                        name="deskripsi_tugas" 
                        x-model="editData.deskripsi_tugas"
                        rows="4"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition resize-none"
                    ></textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                    <input 
                        type="date" 
                        name="deadline" 
                        x-model="editData.deadline_raw"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        @click="$dispatch('close-modal-edit-tugas')"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="isSubmitting"
                        class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center"
                    >
                        <span x-show="!isSubmitting">UPDATE TUGAS</span>
                        <span x-show="isSubmitting">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </x-modal>
    </div>

    <script>
        function editTugasModal() {
            return {
                editData: {
                    id: '',
                    module_id: '',
                    judul_tugas: '',
                    deskripsi_tugas: '',
                    deadline_raw: ''
                },
                openModal(data) {
                    this.editData = {
                        id: data.id,
                        module_id: data.module_id,
                        judul_tugas: data.judul_tugas,
                        deskripsi_tugas: data.deskripsi_tugas === '-' ? '' : data.deskripsi_tugas,
                        deadline_raw: data.deadline_raw
                    };
                }
            }
        }
    </script>
@endsection
