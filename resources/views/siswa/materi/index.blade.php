@extends('layouts.app')

@section('title', 'Materi Belajar')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-6 relative">
    
    <div class="flex justify-between items-center mb-6">
        @if($previousModule)
            <a href="{{ route('siswa.materi.index', ['module' => $previousModule->modul_id]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-lg border-2 border-gray-200 text-gray-700 hover:bg-gray-50 transition-all">
                <x-icon name="chevron-left" class="w-5 h-5" />
                <span class="text-sm font-medium hidden sm:inline">Sebelumnya</span>
            </a>
        @else
            <div class="w-24"></div>
        @endif

        <div class="text-center">
            <span class="text-xs font-bold text-emerald-600 uppercase tracking-wider">Modul Aktif</span>
            <h2 class="text-lg md:text-xl font-bold text-gray-900">{{ $currentModule->nama_modul ?? 'Modul' }}</h2>
            <div class="text-xs text-gray-500 mt-1">Halaman {{ $currentPosition }} dari {{ $totalModules }}</div>
        </div>

        @if($nextModule)
            <a href="{{ route('siswa.materi.index', ['module' => $nextModule->modul_id]) }}" 
               class="inline-flex items-center gap-2 px-4 py-2 bg-emerald-500 text-white rounded-lg hover:bg-emerald-600 transition-all shadow-md hover:shadow-lg">
                <span class="text-sm font-medium hidden sm:inline">Berikutnya</span>
                <x-icon name="chevron-right" class="w-5 h-5" />
            </a>
        @else
            <div class="w-24"></div>
        @endif
    </div>

    <div class="bg-gradient-to-r from-emerald-600 to-emerald-500 rounded-2xl p-6 md:p-8 mb-8 shadow-xl text-white relative overflow-hidden">
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10 blur-2xl"></div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-bold mb-2">Mari Belajar Huruf Hijaiyah</h1>
            <p class="text-emerald-50 text-sm md:text-base max-w-2xl">
                Klik pada salah satu kotak huruf di bawah ini untuk melihat video isyarat tangan dan cara membacanya.
            </p>
        </div>
    </div>

    <div class="mb-12">
        @if($materis->count() > 0)
            <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-4 lg:grid-cols-5 gap-4 md:gap-6">
                @foreach($materis as $materi)
                    <button 
                        onclick="openModal(this)"
                        data-id="{{ $materi->materi_id }}"
                        data-huruf="{{ $materi->huruf_hijaiyah }}"
                        data-judul="{{ $materi->judul_materi }}"
                        data-video="{{ $materi->file_video }}"
                        data-desc="{{ $materi->deskripsi }}"
                        {{-- Pastikan path gambar benar, gunakan asset() --}}
                        data-gambar="{{ $materi->file_path ? asset($materi->file_path) : '' }}"
                        class="group relative bg-white border-2 border-gray-100 rounded-2xl p-6 hover:border-emerald-400 hover:shadow-xl transition-all duration-300 flex flex-col items-center justify-center aspect-square text-center focus:outline-none focus:ring-4 focus:ring-emerald-200"
                    >
                        
                        @if($materi->progress && $materi->progress->first() && $materi->progress->first()->status_2 == 'selesai')
                            <div class="absolute top-3 right-3 w-8 h-8 bg-emerald-500 rounded-full flex items-center justify-center shadow-md animate-fade-in-up">
                                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"/>
                                </svg>
                            </div>
                        @endif
                        
                        <div class="text-6xl md:text-7xl font-bold text-gray-800 mb-4 group-hover:scale-110 transition-transform duration-300 font-arabic">
                            {{ $materi->huruf_hijaiyah ?? '?' }}
                        </div>
                        
                        <div class="text-sm font-bold text-gray-500 uppercase tracking-wide group-hover:text-emerald-600">
                            {{ $materi->judul_materi }}
                        </div>

                        <span class="absolute bottom-2 text-[10px] text-gray-400 opacity-0 group-hover:opacity-100 transition-opacity">
                            Klik untuk belajar
                        </span>
                    </button>
                @endforeach
            </div>
        @else
            <div class="text-center py-20 bg-gray-50 rounded-2xl border-2 border-dashed border-gray-200">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
                <p class="text-gray-500 font-medium">Belum ada materi pada modul ini.</p>
            </div>
        @endif
    </div>

    <div class="border-t border-gray-200 pt-8">
        <h3 class="text-sm font-bold text-gray-400 uppercase tracking-wider mb-4">Navigasi Cepat Modul</h3>
        <div class="flex flex-wrap gap-2">
            @foreach($modules as $module)
                <a href="{{ route('siswa.materi.index', ['module' => $module->modul_id]) }}" 
                   class="px-4 py-2 rounded-lg text-sm font-medium transition-colors {{ $currentModule && $currentModule->modul_id == $module->modul_id ? 'bg-emerald-100 text-emerald-700 border border-emerald-200' : 'bg-white border border-gray-200 text-gray-600 hover:bg-gray-50' }}">
                    {{ $module->nama_modul }}
                </a>
            @endforeach
        </div>
    </div>

</div>

<div id="learningModal" class="fixed inset-0 z-50 hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="fixed inset-0 bg-gray-900/80 backdrop-blur-sm transition-opacity" onclick="closeModal()"></div>

    <div class="fixed inset-0 z-10 overflow-y-auto">
        <div class="flex min-h-full items-center justify-center p-4 text-center sm:p-0">
            
            <div class="relative transform overflow-hidden rounded-2xl bg-white text-left shadow-2xl transition-all sm:my-8 sm:w-full sm:max-w-4xl border border-gray-200">
                
                <div class="bg-gray-50 px-4 py-3 sm:px-6 flex justify-between items-center border-b border-gray-100">
                    <h3 class="text-lg font-bold leading-6 text-gray-900" id="modalTitle">
                        Belajar Huruf <span id="modalHurufTitle" class="text-emerald-600 text-xl ml-1 font-arabic"></span>
                    </h3>
                    <button type="button" onclick="closeModal()" class="rounded-full p-1 hover:bg-gray-200 transition-colors focus:outline-none">
                        <svg class="h-6 w-6 text-gray-500" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>

                <div class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        
                        <div class="space-y-4">
                            <div class="w-full aspect-video bg-black rounded-xl overflow-hidden shadow-lg relative">
                                <iframe id="videoPlayer" class="w-full h-full" src="" allow="autoplay" allowfullscreen></iframe>
                                <div id="videoPlaceholder" class="absolute inset-0 flex items-center justify-center text-white/50 text-sm">
                                    Memuat Video...
                                </div>
                            </div>
                            <p class="text-xs text-center text-gray-500 bg-gray-50 py-2 rounded-lg">
                                <span class="font-bold">Tips:</span> Perhatikan gerakan tangan dan posisi jari.
                            </p>
                        </div>

                        <div class="flex flex-col h-full">
                            <div class="flex-1 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center mb-4 p-4 min-h-[150px]">
                                <img id="staticImage" src="" alt="Panduan Isyarat" class="max-h-48 object-contain hidden">
                                <span id="noImageText" class="text-gray-400 text-sm">Tidak ada gambar panduan</span>
                            </div>

                            <div class="bg-blue-50 p-4 rounded-xl border border-blue-100 mb-4">
                                <h4 class="text-sm font-bold text-blue-800 mb-1">Keterangan:</h4>
                                <p id="modalDeskripsi" class="text-sm text-blue-700 leading-relaxed">
                                    -
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-gray-50 px-4 py-3 sm:flex sm:flex-row-reverse sm:px-6 gap-2">
                    <form id="formSelesai" action="" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex w-full justify-center rounded-lg bg-emerald-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-emerald-500 sm:ml-3 sm:w-auto transition-all transform active:scale-95">
                            ✅ Tandai Selesai
                        </button>
                    </form>
                    
                    <button type="button" onclick="closeModal()" class="mt-3 inline-flex w-full justify-center rounded-lg bg-white px-3 py-2 text-sm font-semibold text-gray-900 shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50 sm:mt-0 sm:w-auto">
                        Tutup
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openModal(element) {
        // 1. Ambil Data dari Atribut Tombol
        const judul = element.getAttribute('data-judul');
        const huruf = element.getAttribute('data-huruf');
        const videoId = element.getAttribute('data-video');
        const gambarSrc = element.getAttribute('data-gambar');
        const deskripsi = element.getAttribute('data-desc');
        const materiId = element.getAttribute('data-id');

        // 2. Update Konten Modal
        document.getElementById('modalHurufTitle').innerText = `( ${huruf} )`;
        document.getElementById('modalDeskripsi').innerText = deskripsi || "Perhatikan video isyarat dengan seksama.";

        // 3. Update Video (Google Drive Embed)
        // Pastikan Video ID valid. Jika link full, mungkin perlu parsing.
        // Asumsi data-video menyimpan ID Google Drive saja (misal: 1A2b3C...)
        const videoUrl = `https://drive.google.com/file/d/${videoId}/preview`;
        document.getElementById('videoPlayer').src = videoUrl;

        // 4. Update Gambar Statis
        const imgEl = document.getElementById('staticImage');
        const noImgEl = document.getElementById('noImageText');
        
        if (gambarSrc) {
            imgEl.src = gambarSrc;
            imgEl.classList.remove('hidden');
            noImgEl.classList.add('hidden');
        } else {
            imgEl.classList.add('hidden');
            noImgEl.classList.remove('hidden');
        }

        // 5. Update Action Form "Tandai Selesai"
        // Sesuaikan route ini dengan route Laravel Anda
        // Contoh: /siswa/materi/{id}/complete
        const formAction = "{{ url('siswa/materi') }}/" + materiId + "/complete";
        document.getElementById('formSelesai').action = formAction;

        // 6. Tampilkan Modal
        document.getElementById('learningModal').classList.remove('hidden');
    }

    function closeModal() {
        // Sembunyikan Modal
        document.getElementById('learningModal').classList.add('hidden');
        
        // Hentikan Video (Reset src agar suara mati)
        document.getElementById('videoPlayer').src = "";
    }

    // Menutup modal jika tombol ESC ditekan
    document.addEventListener('keydown', function(event) {
        if (event.key === "Escape") {
            closeModal();
        }
    });
</script>

@endsection