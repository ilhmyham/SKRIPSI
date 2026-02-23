@extends('layouts.guru')

@section('title', 'Materi ' . $module->nama_modul)
@section('page-title', 'Materi ' . $module->nama_modul)

@section('content')
    <!-- Breadcrumb -->
    <div class="mb-6">
        <nav class="flex items-center gap-2 text-sm text-gray-600">
            <a href="{{ route('guru.materi.index') }}" class="hover:text-emerald-600">Manajemen Materi</a>
            <span>/</span>
            <span class="font-semibold">{{ $module->nama_modul }}</span>
        </nav>
    </div>

    <x-table
        :items="$materis->map(fn($m) => [
            'id' => $m->id,
            'urutan' => $m->urutan ?? '-',
            'judul_materi' => $m->judul_materi,
            'huruf_hijaiyah' => $m->huruf_hijaiyah ?? '-',
            'video_url' => $m->video_url ?? '-',
            'category_id' => $m->category_id ?? '',
            'kategori' => $m->category->nama ?? '-',
            'gambar_isyarat' => $m->gambar_isyarat,
            'deskripsi' => $m->deskripsi ?? '-',
        ])"
        :columns="[
            ['key' => 'urutan', 'label' => '#', 'class' => 'text-center text-gray-500 w-12'],
            ['key' => 'judul_materi', 'label' => 'Judul Materi', 'class' => 'font-bold text-base'],
            ['key' => 'huruf_hijaiyah', 'label' => 'Huruf Hijaiyah', 'class' => 'text-center text-2xl'],
            ['key' => 'video_url', 'label' => 'Video', 'class' => 'text-sm text-gray-600'],
        ]"
        :searchKeys="['judul_materi', 'huruf_hijaiyah']"
    >
        <x-slot:header>
            <button 
                @click="$dispatch('open-modal-create-materi')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-gray-900 text-white font-semibold rounded-lg hover:bg-gray-800 transition"
            >
                <x-icon name="plus" class="w-5 h-5" />
                Tambah Materi
            </button>
        </x-slot:header>

        <x-slot:actions>
            <button 
                @click="$dispatch('open-modal-edit-materi', item)"
                class="text-blue-600 hover:underline text-sm font-medium">
                <x-icon name="edit" class="w-4 h-4 inline" />
                Edit
            </button>
            <form method="POST" :action="`{{ route('guru.materi.index') }}/${item.id}`" 
                  onsubmit="return confirm('Hapus materi ini?')" class="inline">
                @csrf
                @method('DELETE')
                <button type="submit" class="text-red-600 hover:underline text-sm font-medium">
                    <x-icon name="trash" class="w-4 h-4 inline" />
                    Hapus
                </button>
            </form>
        </x-slot:actions>
    </x-table>

    <!-- Create Materi Modal -->
    <x-modal name="create-materi" title="Tambah Materi Baru" description="Tambahkan materi pembelajaran baru." maxWidth="3xl">
        <form method="POST" action="{{ route('guru.materi.store') }}" enctype="multipart/form-data" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
            @csrf

            <input type="hidden" name="module_id" value="{{ $module->id }}">

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Judul Materi</label>
                    <input 
                        type="text" 
                        name="judul_materi" 
                        required
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                        placeholder="Contoh: Belajar Huruf Alif"
                    >
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Huruf Hijaiyah</label>
                    <input 
                        type="text" 
                        name="huruf_hijaiyah"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition text-center text-2xl"
                        placeholder="ا"
                    >
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1.5">
                    Urutan <span class="text-gray-400 font-normal">(Opsional)</span>
                    <x-tooltip text="Isi angka untuk mengatur posisi materi dalam modul. Materi diurutkan dari angka terkecil. Kosongkan jika urutan tidak penting." />
                </label>
                <input 
                    type="number" 
                    name="urutan"
                    min="1"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="Contoh: 1, 2, 3 ... (kosongkan jika tidak perlu urutan)"
                >
                <p class="mt-1 text-xs text-gray-500">Isi angka urutan untuk mengatur posisi materi. Materi tanpa urutan akan tampil di akhir.</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2 flex items-center gap-1.5">
                    Link Video (YouTube atau Google Drive)
                    <x-tooltip text="Gunakan link YouTube penuh (misal: https://youtu.be/xxxx) atau link Google Drive (https://drive.google.com/file/d/xxxx/view). Tambahkan ?start=30&end=60 untuk mulai/akhir detik tertentu." />
                </label>
                <input
                    type="text"
                    name="file_video"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    placeholder="https://youtu.be/xxx atau https://drive.google.com/file/d/xxx/view"
                >
                <p class="mt-1 text-xs text-gray-500">Masukkan link YouTube lengkap atau link Google Drive</p>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Kategori (Opsional)</label>
                <select 
                    name="category_id"
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
                <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Bahasa Isyarat</label>
                <input 
                    type="file" 
                    name="file_path"
                    accept="image/*"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                >
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                <textarea 
                    name="deskripsi" 
                    rows="3"
                    class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition resize-none"
                    placeholder="Jelaskan materi ini..."
                ></textarea>
            </div>

            <div class="flex gap-3 pt-4">
                <button 
                    type="button"
                    @click="$dispatch('close-modal-create-materi')"
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
    <div x-data="editMateriModal()" @open-modal-edit-materi.window="openModal($event.detail)">
        <x-modal name="edit-materi" title="Edit Materi" description="Perbarui informasi materi." maxWidth="3xl">
            <form :action="`{{ route('guru.materi.index') }}/${editData.id}`" method="POST" enctype="multipart/form-data" class="space-y-5" x-data="{ isSubmitting: false }" @submit="isSubmitting = true">
                @csrf
                @method('PUT')

                <input type="hidden" name="module_id" value="{{ $module->id }}">

                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Judul Materi</label>
                        <input 
                            type="text" 
                            name="judul_materi" 
                            x-model="editData.judul_materi"
                            required
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                        >
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Huruf Hijaiyah</label>
                        <input 
                            type="text" 
                            name="huruf_hijaiyah"
                            x-model="editData.huruf_hijaiyah"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition text-center text-2xl"
                        >
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Urutan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <input 
                        type="number" 
                        name="urutan"
                        x-model="editData.urutan"
                        min="1"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                        placeholder="Contoh: 1, 2, 3 ... (kosongkan jika tidak perlu urutan)"
                    >
                    <p class="mt-1 text-xs text-gray-500">Isi angka urutan untuk mengatur posisi materi. Materi tanpa urutan akan tampil di akhir.</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Link Video (YouTube atau Google Drive)</label>
                    <input 
                        type="text" 
                        name="file_video"
                        x-model="editData.file_video"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                        placeholder="https://youtu.be/xxx atau ID Google Drive"
                    >
                    <p class="mt-1 text-xs text-gray-500">Masukkan link YouTube lengkap atau ID video Google Drive</p>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Kategori (Opsional)</label>
                    <select 
                        name="category_id"
                        x-model="editData.category_id"
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
                    <label class="block text-sm font-medium text-gray-700 mb-2">Gambar Bahasa Isyarat (Opsional - Biarkan kosong jika tidak ingin mengganti)</label>
                    <input 
                        type="file" 
                        name="file_path"
                        accept="image/*"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-gray-900 focus:border-transparent transition"
                    >
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Deskripsi</label>
                    <textarea 
                        name="deskripsi" 
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
        function editMateriModal() {
            return {
                editData: {
                    id: '',
                    urutan: '',
                    judul_materi: '',
                    huruf_hijaiyah: '',
                    file_video: '',
                    category_id: '',
                    kategori: '',
                    deskripsi: ''
                },
                openModal(data) {
                    this.editData = {
                        id: data.id,
                        urutan: data.urutan !== '-' ? data.urutan : '',
                        judul_materi: data.judul_materi,
                        huruf_hijaiyah: data.huruf_hijaiyah || '',
                        file_video: data.file_video || '',
                        category_id: data.category_id || '',
                        kategori: data.kategori || '',
                        deskripsi: data.deskripsi || ''
                    };
                }
            }
        }
    </script>
@endsection
