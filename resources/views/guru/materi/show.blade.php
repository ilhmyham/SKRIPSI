@extends('layouts.guru')

@section('title', 'Materi ' . $module->nama_modul)
@section('page-title', 'Materi ' . $module->nama_modul)

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-4 sm:mb-6 overflow-x-auto pb-2">
        <nav class="flex items-center gap-2 text-xs sm:text-sm text-gray-600 whitespace-nowrap">
            <a href="{{ route('guru.materi.index') }}" class="hover:text-emerald-600">Manajemen Materi</a>
            <span>/</span>
            <span class="font-semibold">{{ $module->nama_modul }}</span>
        </nav>
    </div>

    <!-- Tombol Tambah Materi -->
    <div class="mb-4 sm:mb-6 flex justify-start">
        <button 
            @click="$dispatch('open-modal-create-materi')" aria-label="Tambah Materi Baru"
            class="inline-flex items-center gap-2 px-4 py-2 text-sm bg-gray-900 text-white font-semibold rounded hover:bg-gray-800 transition shadow-sm focus-visible:outline-gray-900"
        >
            <x-icon name="plus" class="w-4 h-4" aria-hidden="true" />
            Tambah Materi
        </button>
    </div>

    <x-table
        :items="$materis->map(fn($m) => [
            'id'             => $m->id,
            'urutan'         => $m->urutan ?? '-',
            'judul_materi'   => $m->judul_materi,
            'huruf_hijaiyah' => $m->huruf_hijaiyah ?? '-',
            'video_url'      => $m->file_video ?? '-',
            'kategori_materi_id' => $m->kategori_materi_id ?? '',
            'kategori'       => $m->kategoriMateri->nama ?? '-',
            'file_path'      => $m->path_file,
            'deskripsi'      => $m->deskripsi ?? '-',
        ])"
        :columns="[
            ['key' => 'urutan', 'label' => '#', 'class' => 'text-center text-gray-500 w-12'],
            ['key' => 'judul_materi', 'label' => 'Judul Materi', 'class' => 'font-bold text-base'],
            ['key' => 'huruf_hijaiyah', 'label' => 'Huruf Hijaiyah', 'class' => 'text-center text-2xl'],
            ['key' => 'kategori', 'label' => 'Kategori', 'class' => 'text-sm text-gray-600 font-medium capitalize'],
            ['key' => 'video_url', 'label' => 'Video', 'class' => 'text-sm text-gray-600'],
        ]"
        :searchKeys="['judul_materi', 'huruf_hijaiyah', 'kategori']"
    >


        <x-slot:actions>
            <button 
                @click="$dispatch('set-edit-materi-data', item)" aria-label="Edit Materi"
                class="text-blue-600 hover:underline text-sm font-medium focus-visible:outline-blue-600">
                <x-icon name="edit" class="w-4 h-4 inline" aria-hidden="true" />
                Edit
            </button>
            <form method="POST" :action="`{{ url('guru/materi') }}/${item.id}`" 
                  onsubmit="return confirm('Hapus materi ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" aria-label="Hapus Materi" class="text-red-600 hover:underline text-sm font-medium focus-visible:outline-red-600">
                    <x-icon name="trash" class="w-4 h-4 inline" aria-hidden="true" />
                    Hapus
                </button>
            </form>
        </x-slot:actions>
    </x-table>

    <!-- Create Materi Modal -->
    <x-modal name="create-materi" title="Tambah Materi Baru" description="Tambahkan materi pembelajaran baru." maxWidth="3xl">
        <form id="form-create-materi" method="POST" action="{{ route('guru.materi.store') }}" enctype="multipart/form-data" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
            @csrf

            <input type="hidden" name="modul_iqra_id" value="{{ $module->id }}">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label for="judul_materi" class="block text-sm font-medium text-gray-700 mb-2">Judul Materi</label>
                    <input 
                        type="text" 
                        name="judul_materi" 
                        id="judul_materi"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                        placeholder="Contoh: Belajar Huruf Alif"
                    >
                </div>
                <div>
                    <label for="huruf_hijaiyah" class="block text-sm font-medium text-gray-700 mb-2">Huruf Hijaiyah</label>
                    <input 
                        type="text" 
                        name="huruf_hijaiyah"
                        id="huruf_hijaiyah"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition text-center text-2xl"
                        placeholder="ا"
                    >
                </div>
            </div>

            <div>
                <label for="urutan" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1.5">
                    Urutan <span class="text-gray-400 font-normal">(Opsional)</span>
                    <x-tooltip text="Isi angka untuk mengatur posisi materi dalam modul. Materi diurutkan dari angka terkecil. Kosongkan jika urutan tidak penting." />
                </label>
                <input 
                    type="number" 
                    name="urutan"
                    id="urutan"
                    min="1"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Contoh: 1, 2, 3 ... (kosongkan jika tidak perlu urutan)"
                >
                <p class="mt-1 text-xs text-gray-500">Isi angka urutan untuk mengatur posisi materi. Materi tanpa urutan akan tampil di akhir.</p>
            </div>

            <div>
                <label for="file_video" class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1.5">
                    Link Video (YouTube atau Google Drive)
                    <x-tooltip text="Gunakan link YouTube penuh (misal: https://youtu.be/xxxx) atau link Google Drive (https://drive.google.com/file/d/xxxx/view). Tambahkan ?start=30&end=60 untuk mulai/akhir detik tertentu." />
                </label>
                <input
                    type="text"
                    name="file_video"
                    id="file_video"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="https://youtu.be/xxx atau https://drive.google.com/file/d/xxx/view"
                >
                <p class="mt-1 text-xs text-gray-500">Masukkan link YouTube lengkap atau link Google Drive</p>
            </div>

            <div>
                <label for="kategori_materi_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                <select 
                    name="kategori_materi_id"
                    id="kategori_materi_id"
                    required
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                >
                    <option value="">-- Tidak ada kategori --</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}">{{ Str::title(str_replace('_', ' ', $category->nama)) }}</option>
                    @endforeach
                </select>
                <p class="mt-1 text-xs text-gray-500">Pilih kategori materi ini (jika ada)</p>
            </div>

            <div>
                <label for="path_file" class="block text-sm font-medium text-gray-700 mb-2">Gambar Bahasa Isyarat</label>
                <input 
                    type="file" 
                    name="path_file"
                    id="path_file"
                    accept="image/*"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                >
            </div>

            <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea 
                    name="deskripsi" 
                    id="deskripsi"
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition resize-none"
                    placeholder="Jelaskan materi ini..."
                ></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button 
                    type="button"
                    @click="document.getElementById('form-create-materi').reset(); $dispatch('close-modal-create-materi')"
                    class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                >
                    Batal
                </button>
                <button 
                    type="submit"
                    :disabled="isSubmitting"
                    class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center"
                >
                    <span x-show="!isSubmitting">SIMPAN MATERI</span>
                    <span x-show="isSubmitting">Menyimpan...</span>
                </button>
            </div>
        </form>
    </x-modal>

    <!-- Edit Materi Modal -->
    <div x-data="editMateriModal()">
        <x-modal name="edit-materi" title="Edit Materi" description="Perbarui informasi materi." maxWidth="3xl">
            <form :action="`{{ url('guru/materi') }}/${editData.id}`" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf
                @method('PUT')

                <input type="hidden" name="modul_iqra_id" value="{{ $module->id }}">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label for="edit_judul_materi" class="block text-sm font-medium text-gray-700 mb-2">Judul Materi</label>
                        <input 
                            type="text" 
                            name="judul_materi" 
                            id="edit_judul_materi"
                            x-model="editData.judul_materi"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                        >
                    </div>
                    <div>
                        <label for="edit_huruf_hijaiyah" class="block text-sm font-medium text-gray-700 mb-2">Huruf Hijaiyah</label>
                        <input 
                            type="text" 
                            name="huruf_hijaiyah"
                            id="edit_huruf_hijaiyah"
                            x-model="editData.huruf_hijaiyah"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition text-center text-2xl"
                        >
                    </div>
                </div>

                <div>
                    <label for="edit_urutan" class="block text-sm font-medium text-gray-700 mb-2">Urutan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input 
                        type="number" 
                        name="urutan"
                        id="edit_urutan"
                        x-model="editData.urutan"
                        min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                        placeholder="Contoh: 1, 2, 3 ... (kosongkan jika tidak perlu urutan)"
                    >
                    <p class="mt-1 text-xs text-gray-500">Isi angka urutan untuk mengatur posisi materi. Materi tanpa urutan akan tampil di akhir.</p>
                </div>

                <div>
                    <label for="edit_file_video" class="block text-sm font-medium text-gray-700 mb-2">Link Video (YouTube atau Google Drive)</label>
                    <input 
                        type="text" 
                        name="file_video"
                        id="edit_file_video"
                        x-model="editData.file_video"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                        placeholder="https://youtu.be/xxx atau ID Google Drive"
                    >
                    <p class="mt-1 text-xs text-gray-500">Masukkan link YouTube lengkap atau ID video Google Drive</p>
                </div>

                <div>
                    <label for="edit_kategori_materi_id" class="block text-sm font-medium text-gray-700 mb-2">Kategori</label>
                    <select 
                        name="kategori_materi_id"
                        id="edit_kategori_materi_id"
                        x-model="editData.kategori_materi_id"
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                        <option value="">-- Tidak ada kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}">{{ $category->nama }}</option>
                        @endforeach
                    </select>
                    <p class="mt-1 text-xs text-gray-500">Pilih kategori materi ini (jika ada)</p>
                </div>

                <div>
                    <label for="edit_path_file" class="block text-sm font-medium text-gray-700 mb-2">Gambar Bahasa Isyarat (Opsional - Biarkan kosong jika tidak ingin mengganti)</label>
                    <input 
                        type="file" 
                        name="path_file"
                        id="edit_path_file"
                        accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

                <div>
                    <label for="edit_deskripsi" class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea 
                        name="deskripsi" 
                        id="edit_deskripsi"
                        x-model="editData.deskripsi"
                        rows="3"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition resize-none"
                    ></textarea>
                </div>

                <div class="flex gap-3 pt-4">
                    <button 
                        type="button"
                        @click="$dispatch('close-modal-edit-materi')"
                        class="flex-1 px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 transition"
                    >
                        Batal
                    </button>
                    <button 
                        type="submit"
                        :disabled="isSubmitting"
                        class="flex-1 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition disabled:opacity-50 disabled:cursor-not-allowed flex justify-center items-center"
                    >
                        <span x-show="!isSubmitting">UPDATE MATERI</span>
                        <span x-show="isSubmitting">Menyimpan...</span>
                    </button>
                </div>
            </form>
        </x-modal>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('editMateriModal', () => ({
                editData: {
                    id: '',
                    urutan: '',
                    judul_materi: '',
                    huruf_hijaiyah: '',
                    file_video: '',
                    kategori_materi_id: '',
                    deskripsi: ''
                },
                init() {
                    window.addEventListener('set-edit-materi-data', (e) => {
                        const data = e.detail;
                        this.editData = {
                            id: data.id,
                            urutan: data.urutan !== '-' ? data.urutan : '',
                            judul_materi: data.judul_materi,
                            huruf_hijaiyah: data.huruf_hijaiyah !== '-' ? data.huruf_hijaiyah : '',
                            file_video: data.video_url !== '-' ? data.video_url : '',
                            kategori_materi_id: data.kategori_materi_id || '',
                            deskripsi: data.deskripsi !== '-' ? data.deskripsi : ''
                        };
                        this.$nextTick(() => {
                            window.dispatchEvent(new CustomEvent('open-modal-edit-materi'));
                        });
                    });
                }
            }));
        });
    </script>
@endsection
