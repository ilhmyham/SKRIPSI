@extends('layouts.guru')

@section('title', 'Kelola Tugas')
@section('page-title', 'Manajemen Tugas')

@section('content')
    <div class="mb-4 sm:mb-6 flex justify-start">
        <button 
            @click="$dispatch('open-modal-create-tugas')"
            aria-label="Tambah Tugas Baru"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gray-900 text-white font-semibold rounded hover:bg-gray-800 transition shadow-sm focus-visible:outline-gray-900"
        >
            <x-icon name="plus" class="w-4 h-4" aria-hidden="true" />
            Tambah Tugas
        </button>
    </div>

    <x-table
       :items="$tugasList->map(fn($t) => [
            'id' => $t->id,
            'modul_iqra_id' => $t->modul_iqra_id,
            'judul_tugas' => $t->judul_tugas,
            'deskripsi_tugas' => $t->deskripsi_tugas ?? '-',
            'deadline' => $t->tenggat_waktu->format('d M Y'),
            'tenggat_waktu_raw' => $t->tenggat_waktu->format('Y-m-d'),
            'pengumpulan_count' => $t->pengumpulan_count ?? 0,
        ])"

        :columns="[
            ['key' => 'judul_tugas', 'label' => 'Judul Tugas', 'class' => 'font-bold text-base'],
            ['key' => 'deadline', 'label' => 'Deadline', 'class' => 'text-gray-600'],
            ['key' => 'pengumpulan_count', 'label' => 'Pengumpulan', 'class' => 'text-center'],
        ]"
        :searchKeys="['judul_tugas']"
    >

            <x-slot:actions>
            <a :href="`{{ route('guru.tugas.index') }}/${item.id}/submissions`"
               :aria-label="`Lihat Pengumpulan untuk Tugas ${item.judul_tugas}`"
               class="text-blue-600 hover:underline text-sm font-medium focus-visible:outline-blue-600 rounded px-1">
                <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                </svg>
                Lihat Pengumpulan
            </a>

            <button
                @click="$dispatch('open-modal-edit-tugas', item)"
                :aria-label="`Edit Tugas ${item.judul_tugas}`"
                class="text-green-600 hover:underline text-sm font-medium focus-visible:outline-green-600 rounded px-1"
            >
                <x-icon name="edit" class="w-4 h-4 inline" aria-hidden="true" />
                Edit
            </button>

            <form method="POST" :action="`{{ route('guru.tugas.index') }}/${item.id}`" 
                  onsubmit="return confirm('Hapus tugas ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" 
                        :aria-label="`Hapus Tugas ${item.judul_tugas}`"
                        class="text-red-600 hover:underline text-sm font-medium focus-visible:outline-red-600 rounded px-1">
                    <x-icon name="trash" class="w-4 h-4 inline" aria-hidden="true" />
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
                <label for="create_modul_iqra_id" class="block text-sm font-medium text-gray-700 mb-2">Modul Pembelajaran</label>
                <select name="modul_iqra_id" id="create_modul_iqra_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                    <option value="">-- Pilih Modul --</option>
                    @foreach($modules as $module)
                        <option value="{{ $module->id }}">{{ $module->nama_modul }}</option>
                    @endforeach
                </select>
            </div>

            <div>
                <label for="create_judul_tugas" class="block text-sm font-medium text-gray-700 mb-2">Judul Tugas</label>
                <input 
                    type="text" 
                    name="judul_tugas" 
                    id="create_judul_tugas"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Masukkan judul tugas"
                >
            </div>

            <div>
                <label for="create_deskripsi_tugas" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea 
                    name="deskripsi_tugas" 
                    id="create_deskripsi_tugas"
                    rows="4"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition resize-none"
                    placeholder="Jelaskan detail tugas..."
                ></textarea>
            </div>

            <div>
                <label for="create_tenggat_waktu" class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                <input 
                    type="date" 
                    name="tenggat_waktu" 
                    id="create_tenggat_waktu"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                >
            </div>

            <div class="flex gap-3 pt-4">
                <button 
                    type="button"
                    @click="$dispatch('close-modal-create-tugas')"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition focus-visible:outline-gray-600"
                >
                    Batal
                </button>
                <button 
                    type="submit"
                    :disabled="isSubmitting"
                    class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center focus-visible:outline-gray-900"
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
                    <label for="edit_modul_iqra_id" class="block text-sm font-medium text-gray-700 mb-2">Modul Pembelajaran</label>
                    <select name="modul_iqra_id" id="edit_modul_iqra_id" x-model="editData.modul_iqra_id" required class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition bg-white">
                        <option value="">-- Pilih Modul --</option>
                        @foreach($modules as $module)
                            <option value="{{ $module->id }}">{{ $module->nama_modul }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label for="edit_judul_tugas" class="block text-sm font-medium text-gray-700 mb-2">Judul Tugas</label>
                    <input 
                        type="text" 
                        name="judul_tugas" 
                        id="edit_judul_tugas"
                        x-model="editData.judul_tugas"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

                <div>
                    <label for="edit_deskripsi_tugas" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea 
                        name="deskripsi_tugas" 
                        id="edit_deskripsi_tugas"
                        x-model="editData.deskripsi_tugas"
                        rows="4"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition resize-none"
                    ></textarea>
                </div>

                <div>
                    <label for="edit_tenggat_waktu" class="block text-sm font-medium text-gray-700 mb-2">Deadline</label>
                    <input 
                        type="date" 
                        name="tenggat_waktu" 
                        id="edit_tenggat_waktu"
                        x-model="editData.tenggat_waktu_raw"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        @click="$dispatch('close-modal-edit-tugas')"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition focus-visible:outline-gray-600"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="isSubmitting"
                        class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center focus-visible:outline-gray-900"
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
                    modul_iqra_id: '',
                    judul_tugas: '',
                    deskripsi_tugas: '',
                    tenggat_waktu_raw: ''
                },
                openModal(data) {
                    this.editData = {
                        id: data.id,
                        modul_iqra_id: data.modul_iqra_id,
                        judul_tugas: data.judul_tugas,
                        deskripsi_tugas: data.deskripsi_tugas === '-' ? '' : data.deskripsi_tugas,
                        tenggat_waktu_raw: data.tenggat_waktu_raw
                    };
                }
            }
        }
    </script>
@endsection
