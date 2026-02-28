{{-- resources/views/components/module-card.blade.php --}}

@props([
    'modul',        // Objek modul
    'iteration',    // Angka urutan (loop iteration)
    'href' => '#'   // Link tujuan (opsional, default #)
])

<div class="bg-white rounded-3xl shadow-xl overflow-hidden hover:shadow-2xl transition-all hover:-translate-y-2 group cursor-pointer border border-slate-100 h-full flex flex-col">
    {{-- Bagian Atas / Visual --}}
    <div class="h-48 bg-emerald-100 relative overflow-hidden flex items-center justify-center shrink-0">
        {{-- Pattern Background (Pastikan class pattern-dots ada di CSS kamu atau hapus jika error) --}}
        <div class="absolute inset-0 opacity-10 pattern-dots"></div>
        
        <span class="text-8xl font-black text-emerald-600/20 group-hover:scale-110 transition-transform duration-500 select-none">
            IQRA
        </span>
        
        <div class="absolute bottom-0 text-9xl font-bold text-emerald-900/5 -right-4 -mb-4 select-none">
            {{ $iteration }}
        </div>
    </div>

    {{-- Bagian Konten --}}
    <div class="p-6 flex flex-col flex-1">
        <h3 class="text-2xl font-bold text-slate-800 mb-2">
            {{ $modul->nama_modul }}
        </h3>
        
        <p class="text-slate-500 mb-6 line-clamp-2 flex-1">
            {{ $modul->deskripsi }}
        </p>
        
        {{-- Tombol (Saya ubah jadi <a> agar bisa diklik sebagai link) --}}
        <a href="{{ $href }}" class="w-full bg-emerald-50 text-emerald-700 font-bold py-4 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-all flex items-center justify-center space-x-2 mt-auto">
            <span>Mulai Belajar</span>
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
            </svg>
        </a>
    </div>
</div>