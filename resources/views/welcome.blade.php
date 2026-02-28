<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayat Isyarat - Belajar Bahasa Isyarat Al-Qur'an</title>
    <meta name="description" content="Platform pembelajaran bahasa isyarat huruf hijaiyah.">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;600;700;800;900&display=swap" rel="stylesheet">
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #f8fafc; }
        
        /* Pola titik-titik (dot pattern) untuk menghilangkan kesan kosong */
        .bg-pattern {
            background-image: radial-gradient(#cbd5e1 1.5px, transparent 1.5px);
            background-size: 32px 32px;
        }

        /* Animasi scroll & melayang */
        .reveal { opacity: 0; transform: translateY(30px); transition: all 0.7s ease-out; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .float-slow { animation: float 6s ease-in-out infinite; }
        .float-fast { animation: float 4s ease-in-out infinite reverse; }
        
        @keyframes float {
            0% { transform: translateY(0px); }
            50% { transform: translateY(-15px); }
            100% { transform: translateY(0px); }
        }
    </style>
</head>
<body class="text-slate-800 overflow-x-hidden selection:bg-emerald-500 selection:text-white bg-pattern">

    <nav id="navbar" class="fixed top-0 inset-x-0 z-50 px-6 py-4 flex items-center justify-between backdrop-blur-xl bg-white/70 border-b border-slate-200 transition-all duration-300">
        <a href="/" class="flex items-center gap-3 font-bold text-xl text-slate-900">
            <div class="w-12 h-12 bg-slate-100 rounded-full flex items-center justify-center text-xl shadow-lg shadow-emerald-500/30 text-white"><img src="{{ asset('images/logo.png') }}" alt="Ayat Isyarat" class="h-15 w-15 object-contain"></div>
            Ayat<span class="text-emerald-500">Isyarat</span>
        </a>
        <div class="hidden md:flex items-center gap-8 font-medium text-sm text-slate-600">            
            <a href="{{ route('login') }}" class="px-6 py-2.5 bg-slate-900 text-white rounded-xl hover:-translate-y-1 shadow-lg shadow-slate-900/20 transition-all">Masuk</a>
        </div>
    </nav>

    <section class="relative min-h-screen flex items-center justify-center px-6 pt-32 pb-16 text-center overflow-hidden">
        <div class="absolute top-20 left-1/4 w-[500px] h-[500px] bg-emerald-300/30 rounded-full blur-[100px] -z-10"></div>
        <div class="absolute bottom-20 right-1/4 w-[400px] h-[400px] bg-amber-200/40 rounded-full blur-[100px] -z-10"></div>

        <div class="absolute hidden lg:flex top-40 left-20 bg-white p-4 rounded-2xl shadow-xl border border-slate-100 float-slow items-center gap-3 reveal">
            <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-xl">🎬</div>
            <div class="text-left"><p class="text-xs text-slate-500 font-bold">Video Isyarat</p><p class="text-sm font-black text-slate-800">Tersedia</p></div>
        </div>
       

        <div class="relative z-10 max-w-4xl mx-auto">           
            <div class="text-6xl md:text-8xl font-black text-transparent bg-clip-text bg-gradient-to-r from-emerald-500 to-emerald-700 mb-6 reveal tracking-tight">ا ب ت ث</div>
            
            <h1 class="text-4xl md:text-6xl font-extrabold text-slate-900 mb-6 leading-[1.15] reveal tracking-tight">
                Belajar Huruf Hijaiyah<br>dengan <span class="text-emerald-500 relative">Bahasa Isyarat<svg class="absolute w-full h-3 -bottom-1 left-0 text-amber-400 opacity-60" fill="currentColor" viewBox="0 0 100 10" preserveAspectRatio="none"><path d="M0 5 Q 50 15 100 5 L 100 10 L 0 10 Z"></path></svg></span>
            </h1>
            
            <p class="text-lg text-slate-600 mb-10 max-w-2xl mx-auto reveal leading-relaxed">
                Platform digital untuk mempelajari huruf hijaiyah melalui bahasa isyarat tangan. Menggunakan metode Iqra yang terstruktur, mudah dipahami, dan dapat diakses kapan saja.
            </p>
            
            <div class="flex flex-wrap justify-center gap-4 mb-16 reveal">
                <a href="{{ route('login') }}" class="px-8 py-4 bg-emerald-500 text-white font-bold rounded-2xl shadow-[0_10px_30px_-10px_rgba(16,185,129,0.5)] hover:-translate-y-1 hover:bg-emerald-600 transition-all text-lg">🚀 Mulai Belajar Sekarang</a>
            </div>

            <div class="max-w-2xl mx-auto bg-white p-6 md:p-8 rounded-3xl shadow-xl border border-slate-100 reveal">
    <div class="grid grid-cols-2 divide-x divide-slate-100">
        @foreach([
            ['num'=>'6', 'label'=>'Modul Iqra', 'icon'=>'📚'], 
            ['num'=>'32+', 'label'=>'Huruf', 'icon'=>'🖐️']
        ] as $stat)
            <div class="flex items-center justify-center gap-4 px-4 group transition-all duration-300">
                {{-- Icon dengan efek hover --}}
                <div class="w-14 h-14 bg-slate-50 rounded-2xl flex items-center justify-center text-2xl border border-slate-100 group-hover:bg-emerald-50 group-hover:border-emerald-100 group-hover:scale-110 transition-all duration-300">
                    {{ $stat['icon'] }}
                </div>
                
                {{-- Teks --}}
                <div>
                    <div class="text-3xl font-black text-slate-900 leading-none mb-1">{{ $stat['num'] }}</div>
                    <div class="text-[10px] md:text-xs font-bold text-slate-500 uppercase tracking-widest">{{ $stat['label'] }}</div>
                </div>
            </div>
        @endforeach
    </div>
</div>
    </section>

    @php
        $features = [
            ['icon'=>'🤲', 'title'=>'Gambar Isyarat', 'desc'=>'Visual isyarat tangan yang jelas dan mudah ditiru.'],
            ['icon'=>'🎬', 'title'=>'Video Presisi', 'desc'=>'Video untuk panduan gerak tangan.'],
            ['icon'=>'📝', 'title'=>'Kuis', 'desc'=>'Uji pemahaman dengan soal.'],           
        ];        
    @endphp

    <section id="fitur" class="py-24 px-6 bg-white relative z-10">
        <div class="max-w-6xl mx-auto">
            <div class="text-center mb-16 reveal">
                <span class="px-4 py-1.5 bg-emerald-50 text-emerald-600 font-bold rounded-full text-sm">✨ Keunggulan Sistem</span>
                <h2 class="text-3xl md:text-5xl font-black text-slate-900 mt-4">Belajar Lebih <span class="text-emerald-500">Menyenangkan</span></h2>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($features as $f)
                <div class="bg-slate-50 border border-slate-100 rounded-3xl p-8 hover:-translate-y-2 hover:bg-white hover:shadow-xl hover:border-emerald-200 transition-all reveal group">
                    <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl mb-6 shadow-sm border border-slate-100 group-hover:scale-110 transition-transform">{{ $f['icon'] }}</div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">{{ $f['title'] }}</h3>
                    <p class="text-slate-500 text-sm leading-relaxed">{{ $f['desc'] }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </section>
   

    <section class="py-24 px-6 text-center bg-slate-900 text-white border-t-8 border-emerald-500 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-500/20 rounded-full blur-[80px]"></div>
        
        <div class="relative z-10 max-w-2xl mx-auto reveal">
            <h2 class="text-3xl md:text-5xl font-black mb-6">Mulai Perjalanan Anda</h2>
            <p class="text-slate-300 mb-10 text-lg">Buat akun sekarang dan dapatkan akses penuh ke seluruh materi dan fitur interaktif secara gratis.</p>
            <a href="{{ route('register') }}" class="inline-block px-10 py-4 bg-emerald-500 text-white font-bold rounded-2xl shadow-xl shadow-emerald-500/30 hover:-translate-y-1 hover:bg-emerald-400 transition-all text-lg">Daftar Sekarang — Gratis</a>
        </div>
    </section>

    <footer class="py-8 text-center bg-slate-950 text-sm text-slate-400">
        <p>© 2026 <span class="text-emerald-400 font-bold">AyatIsyarat</span>. Dibuat dengan ❤️ untuk kemudahan belajar.</p>
    </footer>

    <script>
        // Animasi kemunculan
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((e, i) => {
                if (e.isIntersecting) {
                    setTimeout(() => e.target.classList.add('visible'), i * 100);
                    observer.unobserve(e.target);
                }
            });
        }, { threshold: 0.1 });
        document.querySelectorAll('.reveal').forEach(el => observer.observe(el));

        // Efek navbar blur
        window.addEventListener('scroll', () => {
            const nav = document.getElementById('navbar');
            if (window.scrollY > 50) {
                nav.classList.add('shadow-md', 'bg-white/90');
                nav.classList.remove('bg-white/70');
            } else {
                nav.classList.remove('shadow-md', 'bg-white/90');
                nav.classList.add('bg-white/70');
            }
        });
    </script>
</body>
</html>