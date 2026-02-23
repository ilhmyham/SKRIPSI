<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ayat Isyarat - Belajar Bahasa Isyarat Al-Qur'an</title>
    <meta name="description" content="Platform pembelajaran bahasa isyarat huruf hijaiyah berbasis Iqra. Belajar membaca Al-Qur'an dengan bahasa isyarat secara interaktif.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --emerald-50:  #ecfdf5;
            --emerald-100: #d1fae5;
            --emerald-400: #34d399;
            --emerald-500: #10b981;
            --emerald-600: #059669;
            --emerald-700: #047857;
            --emerald-800: #065f46;
            --emerald-900: #064e3b;
            --teal-900:    #134e4a;
            --gold:        #f59e0b;
            --gold-light:  #fde68a;
        }

        html { scroll-behavior: smooth; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background: #0a1628;
            color: #e2e8f0;
            overflow-x: hidden;
        }

        /* ── NAVBAR ── */
        nav {
            position: fixed; top: 0; left: 0; right: 0; z-index: 100;
            padding: 1rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            background: rgba(10, 22, 40, 0.85);
            backdrop-filter: blur(16px);
            border-bottom: 1px solid rgba(52, 211, 153, 0.12);
            transition: background .3s;
        }
        .nav-logo {
            display: flex; align-items: center; gap: .75rem;
            text-decoration: none;
        }
        .nav-logo-icon {
            width: 40px; height: 40px;
            background: linear-gradient(135deg, var(--emerald-500), var(--emerald-700));
            border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem;
            box-shadow: 0 0 20px rgba(16,185,129,.4);
        }
        .nav-logo-text { font-size: 1.1rem; font-weight: 700; color: #fff; }
        .nav-logo-text span { color: var(--emerald-400); }
        .nav-links { display: flex; align-items: center; gap: 2rem; }
        .nav-links a {
            color: #94a3b8; text-decoration: none; font-size: .9rem; font-weight: 500;
            transition: color .2s;
        }
        .nav-links a:hover { color: var(--emerald-400); }
        .btn-nav {
            padding: .55rem 1.4rem;
            background: linear-gradient(135deg, var(--emerald-500), var(--emerald-700));
            color: #fff !important;
            border-radius: 8px;
            font-weight: 600 !important;
            font-size: .875rem !important;
            transition: transform .2s, box-shadow .2s !important;
            box-shadow: 0 4px 15px rgba(16,185,129,.3);
        }
        .btn-nav:hover { transform: translateY(-1px); box-shadow: 0 6px 20px rgba(16,185,129,.45) !important; }

        /* ── HERO ── */
        .hero {
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            position: relative;
            padding: 7rem 2rem 4rem;
            overflow: hidden;
        }
        /* animated gradient background */
        .hero::before {
            content: '';
            position: absolute; inset: 0;
            background:
                radial-gradient(ellipse 80% 60% at 20% 30%, rgba(16,185,129,.18) 0%, transparent 60%),
                radial-gradient(ellipse 60% 50% at 80% 70%, rgba(6,95,70,.25) 0%, transparent 60%),
                radial-gradient(ellipse 40% 40% at 50% 10%, rgba(245,158,11,.08) 0%, transparent 50%);
            animation: bgPulse 8s ease-in-out infinite alternate;
        }
        @keyframes bgPulse {
            from { opacity: .7; }
            to   { opacity: 1; }
        }
        /* floating orbs */
        .orb {
            position: absolute; border-radius: 50%;
            filter: blur(60px); pointer-events: none;
            animation: float 10s ease-in-out infinite alternate;
        }
        .orb-1 { width: 400px; height: 400px; background: rgba(16,185,129,.12); top: -100px; left: -100px; animation-delay: 0s; }
        .orb-2 { width: 300px; height: 300px; background: rgba(245,158,11,.08); bottom: 0; right: -80px; animation-delay: -3s; }
        .orb-3 { width: 200px; height: 200px; background: rgba(52,211,153,.1); top: 40%; left: 60%; animation-delay: -6s; }
        @keyframes float {
            from { transform: translate(0, 0) scale(1); }
            to   { transform: translate(30px, -30px) scale(1.1); }
        }

        .hero-content {
            position: relative; z-index: 2;
            max-width: 800px; text-align: center;
        }
        .hero-badge {
            display: inline-flex; align-items: center; gap: .5rem;
            padding: .4rem 1rem;
            background: rgba(16,185,129,.15);
            border: 1px solid rgba(16,185,129,.3);
            border-radius: 999px;
            font-size: .8rem; font-weight: 600; color: var(--emerald-400);
            margin-bottom: 1.5rem;
            animation: fadeUp .6s ease both;
        }
        .hero-badge-dot {
            width: 7px; height: 7px; border-radius: 50%;
            background: var(--emerald-400);
            animation: blink 1.5s ease-in-out infinite;
        }
        @keyframes blink { 0%,100%{opacity:1} 50%{opacity:.3} }

        .hero-arabic {
            font-size: clamp(3rem, 8vw, 5.5rem);
            font-weight: 900;
            background: linear-gradient(135deg, var(--emerald-400), var(--gold), var(--emerald-400));
            background-size: 200% auto;
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
            animation: shimmer 4s linear infinite, fadeUp .8s ease .1s both;
            line-height: 1.1; margin-bottom: .5rem;
            direction: rtl;
        }
        @keyframes shimmer { to { background-position: 200% center; } }

        .hero-title {
            font-size: clamp(1.6rem, 4vw, 2.8rem);
            font-weight: 800; color: #f1f5f9;
            line-height: 1.2; margin-bottom: 1.25rem;
            animation: fadeUp .8s ease .2s both;
        }
        .hero-title span { color: var(--emerald-400); }

        .hero-desc {
            font-size: 1.05rem; color: #94a3b8; line-height: 1.8;
            max-width: 580px; margin: 0 auto 2.5rem;
            animation: fadeUp .8s ease .3s both;
        }

        .hero-cta {
            display: flex; gap: 1rem; justify-content: center; flex-wrap: wrap;
            animation: fadeUp .8s ease .4s both;
        }
        .btn-primary {
            display: inline-flex; align-items: center; gap: .6rem;
            padding: .9rem 2rem;
            background: linear-gradient(135deg, var(--emerald-500), var(--emerald-700));
            color: #fff; text-decoration: none;
            border-radius: 12px; font-weight: 700; font-size: 1rem;
            box-shadow: 0 8px 30px rgba(16,185,129,.4);
            transition: transform .2s, box-shadow .2s;
        }
        .btn-primary:hover { transform: translateY(-3px); box-shadow: 0 12px 40px rgba(16,185,129,.55); }
        .btn-secondary {
            display: inline-flex; align-items: center; gap: .6rem;
            padding: .9rem 2rem;
            background: rgba(255,255,255,.06);
            border: 1px solid rgba(255,255,255,.15);
            color: #e2e8f0; text-decoration: none;
            border-radius: 12px; font-weight: 600; font-size: 1rem;
            transition: background .2s, border-color .2s, transform .2s;
        }
        .btn-secondary:hover { background: rgba(255,255,255,.1); border-color: var(--emerald-500); transform: translateY(-3px); }

        /* ── STATS ── */
        .stats-bar {
            position: relative; z-index: 2;
            display: flex; justify-content: center; gap: 3rem; flex-wrap: wrap;
            margin-top: 4rem;
            animation: fadeUp .8s ease .5s both;
        }
        .stat-item { text-align: center; }
        .stat-num {
            font-size: 2rem; font-weight: 800;
            background: linear-gradient(135deg, var(--emerald-400), var(--gold));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .stat-label { font-size: .8rem; color: #64748b; margin-top: .2rem; font-weight: 500; }

        @keyframes fadeUp {
            from { opacity: 0; transform: translateY(24px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── SECTION SHARED ── */
        section { padding: 5rem 2rem; }
        .container { max-width: 1100px; margin: 0 auto; }
        .section-label {
            display: inline-block;
            padding: .3rem .9rem;
            background: rgba(16,185,129,.12);
            border: 1px solid rgba(16,185,129,.25);
            border-radius: 999px;
            font-size: .75rem; font-weight: 700; color: var(--emerald-400);
            text-transform: uppercase; letter-spacing: .08em;
            margin-bottom: 1rem;
        }
        .section-title {
            font-size: clamp(1.6rem, 3vw, 2.2rem);
            font-weight: 800; color: #f1f5f9; margin-bottom: .75rem;
        }
        .section-title span { color: var(--emerald-400); }
        .section-desc { color: #64748b; font-size: 1rem; line-height: 1.7; max-width: 520px; }

        /* ── FEATURES ── */
        .features { background: rgba(255,255,255,.02); }
        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 1.5rem; margin-top: 3rem;
        }
        .feature-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 16px; padding: 1.75rem;
            transition: transform .3s, border-color .3s, box-shadow .3s;
            position: relative; overflow: hidden;
        }
        .feature-card::before {
            content: ''; position: absolute; inset: 0;
            background: linear-gradient(135deg, rgba(16,185,129,.06), transparent);
            opacity: 0; transition: opacity .3s;
        }
        .feature-card:hover { transform: translateY(-6px); border-color: rgba(16,185,129,.3); box-shadow: 0 20px 40px rgba(0,0,0,.3); }
        .feature-card:hover::before { opacity: 1; }
        .feature-icon {
            width: 52px; height: 52px;
            background: linear-gradient(135deg, rgba(16,185,129,.2), rgba(16,185,129,.05));
            border: 1px solid rgba(16,185,129,.25);
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem; margin-bottom: 1.25rem;
        }
        .feature-title { font-size: 1.05rem; font-weight: 700; color: #f1f5f9; margin-bottom: .5rem; }
        .feature-desc { font-size: .875rem; color: #64748b; line-height: 1.7; }

        /* ── IQRA MODULES ── */
        .modules-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(160px, 1fr));
            gap: 1rem; margin-top: 3rem;
        }
        .module-card {
            background: rgba(255,255,255,.04);
            border: 1px solid rgba(255,255,255,.08);
            border-radius: 14px; padding: 1.5rem 1rem;
            text-align: center;
            transition: transform .3s, border-color .3s, box-shadow .3s;
            cursor: default;
        }
        .module-card:hover { transform: translateY(-5px); border-color: rgba(16,185,129,.4); box-shadow: 0 16px 32px rgba(0,0,0,.3); }
        .module-num {
            font-size: 2.5rem; font-weight: 900;
            background: linear-gradient(135deg, var(--emerald-400), var(--gold));
            -webkit-background-clip: text; -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        .module-name { font-size: .8rem; font-weight: 700; color: #f1f5f9; margin: .4rem 0 .3rem; }
        .module-desc { font-size: .72rem; color: #64748b; line-height: 1.5; }

        /* ── HOW IT WORKS ── */
        .steps { background: rgba(255,255,255,.02); }
        .steps-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 2rem; margin-top: 3rem;
            position: relative;
        }
        .step-card { text-align: center; position: relative; }
        .step-num {
            width: 56px; height: 56px;
            background: linear-gradient(135deg, var(--emerald-500), var(--emerald-700));
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.3rem; font-weight: 900; color: #fff;
            margin: 0 auto 1.25rem;
            box-shadow: 0 8px 24px rgba(16,185,129,.4);
        }
        .step-title { font-size: 1rem; font-weight: 700; color: #f1f5f9; margin-bottom: .5rem; }
        .step-desc { font-size: .875rem; color: #64748b; line-height: 1.7; }

        /* ── ROLES ── */
        .roles-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 1.5rem; margin-top: 3rem;
        }
        .role-card {
            border-radius: 16px; padding: 2rem;
            position: relative; overflow: hidden;
            transition: transform .3s, box-shadow .3s;
        }
        .role-card:hover { transform: translateY(-6px); box-shadow: 0 24px 48px rgba(0,0,0,.4); }
        .role-card.admin  { background: linear-gradient(135deg, rgba(99,102,241,.15), rgba(99,102,241,.05)); border: 1px solid rgba(99,102,241,.25); }
        .role-card.guru   { background: linear-gradient(135deg, rgba(245,158,11,.15), rgba(245,158,11,.05)); border: 1px solid rgba(245,158,11,.25); }
        .role-card.siswa  { background: linear-gradient(135deg, rgba(16,185,129,.15), rgba(16,185,129,.05)); border: 1px solid rgba(16,185,129,.25); }
        .role-emoji { font-size: 2.5rem; margin-bottom: 1rem; }
        .role-title { font-size: 1.15rem; font-weight: 800; color: #f1f5f9; margin-bottom: .5rem; }
        .role-desc { font-size: .875rem; color: #94a3b8; line-height: 1.7; }
        .role-features { margin-top: 1rem; list-style: none; }
        .role-features li {
            font-size: .8rem; color: #64748b; padding: .3rem 0;
            display: flex; align-items: center; gap: .5rem;
        }
        .role-features li::before { content: '✓'; color: var(--emerald-400); font-weight: 700; }

        /* ── CTA SECTION ── */
        .cta-section {
            text-align: center;
            background: linear-gradient(135deg, rgba(16,185,129,.08), rgba(6,95,70,.12));
            border-top: 1px solid rgba(16,185,129,.15);
            border-bottom: 1px solid rgba(16,185,129,.15);
        }
        .cta-section .section-title { font-size: clamp(1.8rem, 4vw, 2.5rem); }
        .cta-section .section-desc { max-width: 500px; margin: 0 auto 2.5rem; }

        /* ── FOOTER ── */
        footer {
            padding: 2rem;
            text-align: center;
            border-top: 1px solid rgba(255,255,255,.06);
            color: #475569; font-size: .85rem;
        }
        footer span { color: var(--emerald-500); }

        /* ── SCROLL ANIMATIONS ── */
        .reveal {
            opacity: 0; transform: translateY(30px);
            transition: opacity .7s ease, transform .7s ease;
        }
        .reveal.visible { opacity: 1; transform: translateY(0); }

        /* ── RESPONSIVE ── */
        @media (max-width: 640px) {
            .nav-links { display: none; }
            .stats-bar { gap: 1.5rem; }
            .hero-arabic { font-size: 3rem; }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <nav>
        <a href="/" class="nav-logo">
            <div class="nav-logo-icon">🤲</div>
            <span class="nav-logo-text">Ayat<span>Isyarat</span></span>
        </a>
        <div class="nav-links">
            <a href="#fitur">Fitur</a>
            <a href="#modul">Modul</a>
            <a href="#cara-kerja">Cara Kerja</a>
            <a href="{{ route('login') }}" class="btn-nav">Masuk</a>
        </div>
    </nav>

    <!-- HERO -->
    <section class="hero">
        <div class="orb orb-1"></div>
        <div class="orb orb-2"></div>
        <div class="orb orb-3"></div>

        <div class="hero-content">
            <div class="hero-badge">
                <div class="hero-badge-dot"></div>
                Platform Pembelajaran Interaktif
            </div>

            <div class="hero-arabic">ا ب ت ث</div>

            <h1 class="hero-title">
                Belajar Huruf Hijaiyah<br>
                dengan <span>Bahasa Isyarat</span>
            </h1>

            <p class="hero-desc">
                Platform digital untuk mempelajari huruf hijaiyah melalui bahasa isyarat tangan.
                Metode Iqra yang interaktif, mudah dipahami, dan dapat diakses kapan saja.
            </p>

            <div class="hero-cta">
                <a href="{{ route('login') }}" class="btn-primary" id="btn-mulai">
                    🚀 Mulai Belajar
                </a>
                <a href="#fitur" class="btn-secondary">
                    📖 Pelajari Lebih Lanjut
                </a>
            </div>

            <div class="stats-bar">
                <div class="stat-item">
                    <div class="stat-num">6</div>
                    <div class="stat-label">Modul Iqra</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">32+</div>
                    <div class="stat-label">Huruf Hijaiyah</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">100%</div>
                    <div class="stat-label">Gratis</div>
                </div>
                <div class="stat-item">
                    <div class="stat-num">3</div>
                    <div class="stat-label">Peran Pengguna</div>
                </div>
            </div>
        </div>
    </section>

    <!-- FEATURES -->
    <section class="features" id="fitur">
        <div class="container">
            <div class="reveal">
                <span class="section-label">✨ Fitur Unggulan</span>
                <h2 class="section-title">Semua yang Anda Butuhkan untuk <span>Belajar</span></h2>
                <p class="section-desc">Dirancang khusus untuk memudahkan pembelajaran bahasa isyarat Al-Qur'an secara mandiri maupun terbimbing.</p>
            </div>

            <div class="features-grid">
                <div class="feature-card reveal">
                    <div class="feature-icon">🤲</div>
                    <div class="feature-title">Gambar Isyarat Tangan</div>
                    <div class="feature-desc">Setiap huruf hijaiyah dilengkapi gambar isyarat tangan yang jelas dan mudah ditiru.</div>
                </div>
                <div class="feature-card reveal">
                    <div class="feature-icon">🎬</div>
                    <div class="feature-title">Video Pembelajaran</div>
                    <div class="feature-desc">Video YouTube dengan timestamp presisi untuk setiap huruf agar belajar lebih efektif.</div>
                </div>
                <div class="feature-card reveal">
                    <div class="feature-icon">📝</div>
                    <div class="feature-title">Kuis Interaktif</div>
                    <div class="feature-desc">Uji pemahaman dengan kuis pilihan ganda yang dirancang oleh guru berpengalaman.</div>
                </div>
                <div class="feature-card reveal">
                    <div class="feature-icon">📊</div>
                    <div class="feature-title">Pantau Progres</div>
                    <div class="feature-desc">Lacak perkembangan belajar siswa secara real-time melalui dashboard yang informatif.</div>
                </div>
                <div class="feature-card reveal">
                    <div class="feature-icon">📚</div>
                    <div class="feature-title">6 Modul Iqra</div>
                    <div class="feature-desc">Materi tersusun rapi dari Iqra 1 hingga Iqra 6, dari dasar hingga tingkat lanjut.</div>
                </div>
                <div class="feature-card reveal">
                    <div class="feature-icon">📋</div>
                    <div class="feature-title">Tugas & Pengumpulan</div>
                    <div class="feature-desc">Guru dapat memberi tugas dan siswa mengumpulkan langsung di platform.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- MODULES -->
    <section id="modul">
        <div class="container">
            <div class="reveal">
                <span class="section-label">📖 Kurikulum</span>
                <h2 class="section-title">6 Modul <span>Iqra</span> Lengkap</h2>
                <p class="section-desc">Materi disusun secara bertahap mengikuti metode Iqra yang telah terbukti efektif.</p>
            </div>

            <div class="modules-grid">
                <div class="module-card reveal">
                    <div class="module-num">١</div>
                    <div class="module-name">Iqra 1</div>
                    <div class="module-desc">Huruf Hijaiyah Dasar (32 huruf)</div>
                </div>
                <div class="module-card reveal">
                    <div class="module-num">٢</div>
                    <div class="module-name">Iqra 2</div>
                    <div class="module-desc">Fathah, Kasrah & Dhomah</div>
                </div>
                <div class="module-card reveal">
                    <div class="module-num">٣</div>
                    <div class="module-name">Iqra 3</div>
                    <div class="module-desc">Tanwin, Sukun & Tasydid</div>
                </div>
                <div class="module-card reveal">
                    <div class="module-num">٤</div>
                    <div class="module-name">Iqra 4</div>
                    <div class="module-desc">Konsep Sambung & Latihan Kata</div>
                </div>
                <div class="module-card reveal">
                    <div class="module-num">٥</div>
                    <div class="module-name">Iqra 5</div>
                    <div class="module-desc">Bacaan Mad (Panjang)</div>
                </div>
                <div class="module-card reveal">
                    <div class="module-num">٦</div>
                    <div class="module-name">Iqra 6</div>
                    <div class="module-desc">Simbol Khusus & Waqaf</div>
                </div>
            </div>
        </div>
    </section>

    <!-- HOW IT WORKS -->
    <section class="steps" id="cara-kerja">
        <div class="container">
            <div class="reveal" style="text-align:center">
                <span class="section-label">🔄 Cara Kerja</span>
                <h2 class="section-title">Mulai Belajar dalam <span>3 Langkah</span></h2>
            </div>

            <div class="steps-grid">
                <div class="step-card reveal">
                    <div class="step-num">1</div>
                    <div class="step-title">Daftar & Masuk</div>
                    <div class="step-desc">Buat akun siswa atau masuk dengan akun yang sudah ada. Proses cepat dan mudah.</div>
                </div>
                <div class="step-card reveal">
                    <div class="step-num">2</div>
                    <div class="step-title">Pilih Modul</div>
                    <div class="step-desc">Pilih modul Iqra sesuai level Anda. Mulai dari Iqra 1 untuk pemula.</div>
                </div>
                <div class="step-card reveal">
                    <div class="step-num">3</div>
                    <div class="step-title">Belajar & Berlatih</div>
                    <div class="step-desc">Pelajari materi, tonton video, dan uji kemampuan dengan kuis interaktif.</div>
                </div>
            </div>
        </div>
    </section>

    <!-- ROLES -->
    <section>
        <div class="container">
            <div class="reveal">
                <span class="section-label">👥 Pengguna</span>
                <h2 class="section-title">Dirancang untuk <span>Semua Peran</span></h2>
                <p class="section-desc">Platform ini mendukung tiga peran pengguna dengan fitur yang disesuaikan.</p>
            </div>

            <div class="roles-grid">
                <div class="role-card admin reveal">
                    <div class="role-emoji">🛡️</div>
                    <div class="role-title">Admin</div>
                    <div class="role-desc">Kelola seluruh sistem, pengguna, dan konten platform.</div>
                    <ul class="role-features">
                        <li>Manajemen pengguna</li>
                        <li>Kelola modul & materi</li>
                        <li>Pantau aktivitas</li>
                        <li>Kelola kuis</li>
                    </ul>
                </div>
                <div class="role-card guru reveal">
                    <div class="role-emoji">👨‍🏫</div>
                    <div class="role-title">Guru</div>
                    <div class="role-desc">Buat materi, kuis, dan pantau perkembangan siswa.</div>
                    <ul class="role-features">
                        <li>Tambah & edit materi</li>
                        <li>Buat kuis & tugas</li>
                        <li>Pantau progres siswa</li>
                        <li>Nilai pengumpulan</li>
                    </ul>
                </div>
                <div class="role-card siswa reveal">
                    <div class="role-emoji">🎓</div>
                    <div class="role-title">Siswa</div>
                    <div class="role-desc">Belajar materi, ikuti kuis, dan kumpulkan tugas.</div>
                    <ul class="role-features">
                        <li>Akses semua materi</li>
                        <li>Ikuti kuis interaktif</li>
                        <li>Kumpulkan tugas</li>
                        <li>Lihat progres belajar</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA -->
    <section class="cta-section">
        <div class="container reveal">
            <span class="section-label">🚀 Mulai Sekarang</span>
            <h2 class="section-title">Siap Belajar Bahasa Isyarat <span>Al-Qur'an</span>?</h2>
            <p class="section-desc">Bergabunglah dan mulai perjalanan belajar Anda hari ini. Gratis dan mudah diakses.</p>
            <div class="hero-cta">
                <a href="{{ route('login') }}" class="btn-primary">
                    🤲 Masuk Sekarang
                </a>
                <a href="{{ route('register') }}" class="btn-secondary">
                    📝 Daftar Gratis
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer>
        <p>© 2026 <span>AyatIsyarat</span> · Platform Pembelajaran Bahasa Isyarat Huruf Hijaiyah</p>
        <p style="margin-top:.5rem; font-size:.75rem; color:#334155;">Dibuat dengan ❤️ untuk kemudahan belajar Al-Qur'an</p>
    </footer>

    <script>
        // Scroll reveal animation
        const reveals = document.querySelectorAll('.reveal');
        const observer = new IntersectionObserver((entries) => {
            entries.forEach((entry, i) => {
                if (entry.isIntersecting) {
                    setTimeout(() => entry.target.classList.add('visible'), i * 80);
                    observer.unobserve(entry.target);
                }
            });
        }, { threshold: 0.1 });
        reveals.forEach(el => observer.observe(el));

        // Navbar scroll effect
        window.addEventListener('scroll', () => {
            document.querySelector('nav').style.background =
                window.scrollY > 50
                    ? 'rgba(10,22,40,0.97)'
                    : 'rgba(10,22,40,0.85)';
        });
    </script>
</body>
</html>
