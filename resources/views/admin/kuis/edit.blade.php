@extends('layouts.admin')

@section('title', 'Edit Kuis')
@section('page-title', 'Edit Kuis')

@section('content')
<div class="max-w-6xl mx-auto" x-data="quizBuilder()">
    <form method="POST" action="{{ route('admin.kuis.update', $kuis) }}" enctype="multipart/form-data" @submit="validateBeforeSubmit">
        @csrf
        @method('PUT')

        <!-- Success/Error Messages -->
        @if(session('success'))
            <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <strong>Terjadi kesalahan:</strong>
                <ul class="list-disc list-inside mt-2">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <x-card title="Informasi Kuis" class="mb-6">
            <!-- Module Selection -->
            <div class="mb-6">
                <label class="block text-lg font-semibold mb-2">Modul</label>
                <select name="module_id" required
                        class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:border-emerald-500 focus:ring focus:ring-emerald-200">
                    @foreach($modules as $module)
                        <option value="{{ $module->id }}" {{ $kuis->module_id == $module->id ? 'selected' : '' }}>
                            {{ $module->nama_modul }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Quiz Title -->
            <div class="mb-6">
                <label class="block text-lg font-semibold mb-2">Judul Kuis</label>
                <input type="text" name="judul_kuis" required value="{{ old('judul_kuis', $kuis->judul_kuis) }}"
                       class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:border-emerald-500 focus:ring focus:ring-emerald-200">
            </div>

            <!-- Description -->
            <div>
                <label class="block text-lg font-semibold mb-2">Deskripsi (Opsional)</label>
                <textarea name="deskripsi" rows="3"
                          class="w-full px-4 py-3 text-lg border-2 border-gray-300 rounded-lg focus:border-emerald-500 focus:ring focus:ring-emerald-200">{{ old('deskripsi', $kuis->deskripsi) }}</textarea>
            </div>
        </x-card>

        <!-- Questions Section -->
        <x-card class="mb-6">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-2xl font-bold">Pertanyaan</h2>
                <button type="button" @click="addQuestion" 
                        class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700 transition">
                    <x-icon name="plus" class="w-5 h-5" />
                    Tambah Pertanyaan
                </button>
            </div>

            <!-- Questions List -->
            <div class="space-y-6">
                <template x-for="(question, qIndex) in pertanyaan" :key="qIndex">
                    <div class="p-6 bg-gray-50 rounded-lg border-2 border-gray-200">
                        <div class="flex justify-between items-start mb-4">
                            <h3 class="text-xl font-bold">Pertanyaan <span x-text="qIndex + 1"></span></h3>
                            <button type="button" @click="removeQuestion(qIndex)" 
                                    class="text-red-600 hover:text-red-800">
                                <x-icon name="trash" class="w-6 h-6" />
                            </button>
                        </div>

                        <!-- Question Text -->
                        <div class="mb-4">
                            <label class="block font-semibold mb-2">Teks Pertanyaan (Optional jika ada gambar)</label>
                            <input type="text" 
                                   :name="`pertanyaan[${qIndex}][text_pertanyaan]`" 
                                   x-model="question.text_pertanyaan"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-emerald-500"
                                   placeholder="Tulis pertanyaan...">
                            
                            {{-- Hidden: id soal lama (agar controller tahu ini update, bukan create baru) --}}
                            <input type="hidden" 
                                   :name="`pertanyaan[${qIndex}][id]`" 
                                   :value="question.id ?? ''">

                            <!-- Hidden field for existing image path -->
                            <input type="hidden" 
                                   :name="`pertanyaan[${qIndex}][existing_gambar_pertanyaan]`" 
                                   :value="question.existing_gambar">
                        </div>

                        <!-- Question Image Upload -->
                        <div class="mb-4">
                            <label class="block font-semibold mb-2">
                                <span class="flex items-center gap-2">
                                    <x-icon name="image" class="w-5 h-5" />
                                    Gambar Pertanyaan (Optional)
                                </span>
                            </label>
                            
                            <!-- Show existing image if exists -->
                            <div x-show="question.existing_gambar && !question.gambar_preview" class="mb-2">
                                <div class="relative inline-block">
                                    <img :src="'/storage/' + question.existing_gambar" class="max-w-xs rounded-lg border-2 border-gray-300">
                                    <div class="mt-2 text-sm text-gray-600">Gambar saat ini</div>
                                </div>
                            </div>
                            
                            <input type="file" 
                                   :name="`pertanyaan[${qIndex}][gambar_pertanyaan]`"
                                   accept="image/*"
                                   @change="handleQuestionImage(qIndex, $event)"
                                   class="hidden"
                                   :id="'q-img-' + qIndex">
                            
                            <div x-show="!question.gambar_preview">
                                <label :for="'q-img-' + qIndex" 
                                       class="inline-flex items-center gap-2 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 cursor-pointer transition">
                                    <x-icon name="upload" class="w-5 h-5" />
                                    <span x-text="question.existing_gambar ? 'Ganti Gambar' : 'Pilih Gambar'"></span>
                                </label>
                            </div>
                            
                            <!-- Show new preview if file selected -->
                            <div x-show="question.gambar_preview" class="mt-2">
                                <div class="relative inline-block">
                                    <img :src="question.gambar_preview" class="max-w-xs rounded-lg border-2 border-gray-300">
                                    <button type="button" 
                                            @click="removeQuestionImage(qIndex)" 
                                            class="absolute top-2 right-2 bg-red-600 text-white p-2 rounded-full hover:bg-red-700">
                                        <x-icon name="x" class="w-4 h-4" />
                                    </button>
                                    <div class="mt-2 text-sm text-green-600">Preview gambar baru</div>
                                </div>
                            </div>
                        </div>

                        <!-- Options -->
                        <div class="mb-4">
                            <div class="flex justify-between items-center mb-2">
                                <label class="block font-semibold">Pilihan Jawaban</label>
                                <button type="button" 
                                        @click="addOption(qIndex)"
                                        class="text-sm px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600 transition">
                                    + Tambah Opsi
                                </button>
                            </div>
                            <div class="space-y-3">
                                <template x-for="(opsi, oIndex) in question.opsi" :key="oIndex">
                                    <div class="p-3 bg-white rounded-lg border-2 border-gray-200">
                                        <div class="flex items-start gap-3 mb-2">
                                            <!-- Correct Answer Radio -->
                                            <input type="radio" 
                                                   :name="'correct_' + qIndex"
                                                   :checked="opsi.is_benar"
                                                   @change="markCorrect(qIndex, oIndex)"
                                                   class="w-5 h-5 text-emerald-600 mt-1">
                                            
                                            <!-- Option Text -->
                                            <div class="flex-1">
                                                <input type="text" 
                                                       :name="`pertanyaan[${qIndex}][opsi][${oIndex}][teks_opsi]`"
                                                       x-model="opsi.teks_opsi"
                                                       class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:border-emerald-500"
                                                       :placeholder="'Opsi ' + (oIndex + 1) + ' (optional jika pakai gambar)'">
                                                
                                                <!-- Hidden is_benar input -->
                                                <input type="hidden" 
                                                       :name="`pertanyaan[${qIndex}][opsi][${oIndex}][is_benar]`" 
                                                       :value="opsi.is_benar ? 1 : 0">

                                                {{-- Hidden: id opsi lama (agar controller tahu ini update, bukan create baru) --}}
                                                <input type="hidden" 
                                                       :name="`pertanyaan[${qIndex}][opsi][${oIndex}][id]`" 
                                                       :value="opsi.id ?? ''">
                                                
                                                <!-- Hidden field for existing option image -->
                                                <input type="hidden" 
                                                       :name="`pertanyaan[${qIndex}][opsi][${oIndex}][existing_gambar_opsi]`" 
                                                       :value="opsi.existing_gambar">
                                            </div>
                                            
                                            <!-- Correct Badge -->
                                            <span x-show="opsi.is_benar" 
                                                  class="text-sm font-semibold text-green-600 mt-1 whitespace-nowrap">
                                                ✓ Benar
                                            </span>
                                            
                                            <!-- Remove Option Button -->
                                            <button type="button" 
                                                    @click="removeOption(qIndex, oIndex)"
                                                    x-show="question.opsi.length > 2"
                                                    class="text-red-600 hover:text-red-800 mt-1"
                                                    title="Hapus opsi">
                                                <x-icon name="x" class="w-5 h-5" />
                                            </button>
                                        </div>

                                        <!-- Option Image Upload -->
                                        <div class="ml-8">
                                            <!-- Show existing option image if exists -->
                                            <div x-show="opsi.existing_gambar && !opsi.gambar_preview" class="mb-2">
                                                <div class="relative inline-block">
                                                    <img :src="'/storage/' + opsi.existing_gambar" class="max-w-xs rounded border border-gray-300">
                                                    <div class="mt-1 text-xs text-gray-600">Gambar saat ini</div>
                                                </div>
                                            </div>
                                            
                                            <input type="file" 
                                                   :name="`pertanyaan[${qIndex}][opsi][${oIndex}][gambar_opsi]`"
                                                   accept="image/*"
                                                   @change="handleOptionImage(qIndex, oIndex, $event)"
                                                   class="hidden"
                                                   :id="'o-img-' + qIndex + '-' + oIndex">
                                            
                                            <div x-show="!opsi.gambar_preview">
                                                <label :for="'o-img-' + qIndex + '-' + oIndex" 
                                                       class="inline-flex items-center gap-1 px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded hover:bg-gray-200 cursor-pointer transition">
                                                    <x-icon name="image" class="w-4 h-4" />
                                                    <span x-text="opsi.existing_gambar ? 'Ganti Gambar' : 'Tambah Gambar'"></span>
                                                </label>
                                            </div>
                                            
                                            <!-- Show new preview if file selected -->
                                            <div x-show="opsi.gambar_preview" class="mt-2">
                                                <div class="relative inline-block">
                                                    <img :src="opsi.gambar_preview" class="max-w-xs rounded border border-gray-300">
                                                    <button type="button" 
                                                            @click="removeOptionImage(qIndex, oIndex)" 
                                                            class="absolute top-1 right-1 bg-red-600 text-white p-1 rounded-full hover:bg-red-700">
                                                        <x-icon name="x" class="w-3 h-3" />
                                                    </button>
                                                    <div class="mt-1 text-xs text-green-600">Preview gambar baru</div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </template>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">
                                Pilih jawaban yang benar dengan klik radio button
                            </p>
                        </div>
                    </div>
                </template>
            </div>
        </x-card>

        <!-- Submit Buttons -->
        <div class="flex gap-4">
            <button type="submit" 
                    class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition font-semibold"
                    x-bind:disabled="pertanyaan.length === 0">
                <x-icon name="check" class="w-6 h-6" />
                Update Kuis
            </button>
            <a href="{{ route('admin.kuis.by-module', $kuis->module_id) }}" 
               class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition font-semibold text-center">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
function quizBuilder() {
    return {
        pertanyaan: @json($quizData),
        
        addQuestion() {
            this.pertanyaan.push({
                text_pertanyaan: '',
                existing_gambar: null,
                gambar_preview: null,
                opsi: [
                    { teks_opsi: '', existing_gambar: null, gambar_preview: null, is_benar: true },
                    { teks_opsi: '', existing_gambar: null, gambar_preview: null, is_benar: false },
                    { teks_opsi: '', existing_gambar: null, gambar_preview: null, is_benar: false },
                    { teks_opsi: '', existing_gambar: null, gambar_preview: null, is_benar: false }
                ]
            });
        },
        
        removeQuestion(index) {
            if (confirm('Hapus pertanyaan ini?')) {
                this.pertanyaan.splice(index, 1);
            }
        },
        
        markCorrect(qIndex, oIndex) {
            this.pertanyaan[qIndex].opsi.forEach((opsi, i) => {
                opsi.is_benar = (i === oIndex);
            });
        },
        
        addOption(qIndex) {
            this.pertanyaan[qIndex].opsi.push({
                teks_opsi: '',
                existing_gambar: null,
                gambar_preview: null,
                is_benar: false
            });
        },
        
        removeOption(qIndex, oIndex) {
            if (this.pertanyaan[qIndex].opsi.length <= 2) {
                alert('Minimal harus ada 2 opsi jawaban');
                return;
            }
            
            if (confirm('Hapus opsi ini?')) {
                const wasCorrect = this.pertanyaan[qIndex].opsi[oIndex].is_benar;
                this.pertanyaan[qIndex].opsi.splice(oIndex, 1);
                
                // If removed option was correct, mark first option as correct
                if (wasCorrect && this.pertanyaan[qIndex].opsi.length > 0) {
                    this.pertanyaan[qIndex].opsi[0].is_benar = true;
                }
            }
        },
        
        handleQuestionImage(qIndex, event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.pertanyaan[qIndex].gambar_preview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        removeQuestionImage(qIndex) {
            this.pertanyaan[qIndex].gambar_preview = null;
            this.pertanyaan[qIndex].existing_gambar = null;
            const input = document.getElementById('q-img-' + qIndex);
            if (input) input.value = '';
        },
        
        handleOptionImage(qIndex, oIndex, event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = (e) => {
                    this.pertanyaan[qIndex].opsi[oIndex].gambar_preview = e.target.result;
                };
                reader.readAsDataURL(file);
            }
        },
        
        removeOptionImage(qIndex, oIndex) {
            this.pertanyaan[qIndex].opsi[oIndex].gambar_preview = null;
            this.pertanyaan[qIndex].opsi[oIndex].existing_gambar = null;
            const input = document.getElementById('o-img-' + qIndex + '-' + oIndex);
            if (input) input.value = '';
        },
        
        validateBeforeSubmit(e) {
            if (this.pertanyaan.length === 0) {
                e.preventDefault();
                alert('Tambahkan minimal 1 pertanyaan');
                return false;
            }
            // Let form submit naturally if validation passes
            return true;
        }
    }
}
</script>
@endsection
