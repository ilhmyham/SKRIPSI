@extends('layouts.siswa')

@section('title', 'Profil Saya')

@section('content')
<div class="pb-24">

    {{-- ── HERO HEADER ── --}}
    <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-900 pt-10 pb-20 px-5 relative overflow-hidden">
        {{-- Orbs --}}
        <div class="absolute top-0 right-0 w-64 h-64 bg-emerald-400/20 rounded-full blur-3xl -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
        <div class="absolute bottom-0 left-0 w-48 h-48 bg-amber-400/15 rounded-full blur-3xl translate-y-1/2 -translate-x-1/2 pointer-events-none"></div>

        <div class="max-w-2xl mx-auto relative z-10 flex flex-col items-center text-center gap-4">
            {{-- Avatar --}}
            <div class="w-24 h-24 rounded-full ring-4 ring-white/30 shadow-2xl overflow-hidden bg-white/20 flex items-center justify-center">
                @if($user->avatar)
                    @if(str_starts_with($user->avatar, 'http'))
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @endif
                @else
                    <svg class="w-12 h-12 text-white/80" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>

            {{-- Name & badge --}}
            <div>
                <h1 class="text-2xl font-black text-white leading-tight">{{ $user->name }}</h1>
                <p class="text-emerald-200 text-sm mt-0.5">{{ $user->email }}</p>
                <span class="inline-block mt-2 px-3 py-0.5 bg-amber-400/20 border border-amber-400/40 text-amber-300 text-xs font-bold rounded-full uppercase tracking-widest">
                    Siswa
                </span>
            </div>

            {{-- Logout --}}
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit"
                        class="mt-1 inline-flex items-center gap-2 px-4 py-2 rounded-xl bg-red-600 hover:bg-red-700 border border-red-600 text-white text-sm font-semibold transition-all duration-200">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>

    {{-- ── STAT CHIPS (overlap hero) ── --}}
    <div class="max-w-2xl mx-auto px-4 -mt-10 relative z-10">
        <div class="grid grid-cols-3 gap-3">
            @foreach([
                ['val' => number_format($overallProgress, 0) . '%', 'label' => 'Progress',      'from' => 'from-emerald-500', 'to' => 'to-emerald-700'],
                ['val' => $completedMateri . '/' . $totalMateri,     'label' => 'Materi Selesai', 'from' => 'from-blue-500',   'to' => 'to-blue-700'],
                ['val' => $completedKuis . '/' . $totalKuis,         'label' => 'Kuis Dikerjakan','from' => 'from-violet-500', 'to' => 'to-violet-700'],
            ] as $chip)
                <div class="bg-white rounded-2xl p-3 shadow-lg border border-gray-100 text-center">
                    <p class="text-xl font-black bg-gradient-to-br {{ $chip['from'] }} {{ $chip['to'] }} bg-clip-text text-transparent leading-none">
                        {{ $chip['val'] }}
                    </p>
                    <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mt-1 leading-tight">{{ $chip['label'] }}</p>
                </div>
            @endforeach
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 mt-5 space-y-5">

        {{-- ── PROFILE FORM CARD ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden"
             x-data="{
                editing: false,
                showPassword: false,
                showPasswordConfirm: false,
                password: '',
                passwordConfirm: '',
                photoPreview: null,
                photoFile: null,
                updatePhotoPreview() {
                    const file = this.$refs.photoInput.files[0];
                    if (file) {
                        this.photoFile = file;
                        const reader = new FileReader();
                        reader.onload = (e) => { this.photoPreview = e.target.result; };
                        reader.readAsDataURL(file);
                    }
                }
             }">

            {{-- Card Header --}}
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="text-base font-black text-gray-800">Informasi Profil</h2>
                <button @click="editing = !editing; if(!editing){ password=''; passwordConfirm=''; }"
                        type="button"
                        class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl text-xs font-bold transition-all duration-200"
                        :class="editing
                            ? 'bg-gray-100 text-gray-600 hover:bg-gray-200'
                            : 'bg-emerald-600 text-white hover:bg-emerald-700 shadow-sm shadow-emerald-300/40'">
                    <svg x-show="!editing" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    <svg x-show="editing" class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24" style="display:none">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                    <span x-text="editing ? 'Batal' : 'Edit'"></span>
                </button>
            </div>

            {{-- Form --}}
            <form method="POST" action="{{ route('siswa.profile.update') }}" enctype="multipart/form-data" class="px-5 py-4 space-y-4">
                @csrf
                @method('PUT')

                {{-- Photo Upload (only when editing) --}}
                <div x-show="editing" style="display:none" class="flex items-center gap-4 p-3 bg-emerald-50 rounded-xl border border-emerald-100">
                    <div class="w-16 h-16 rounded-full overflow-hidden bg-emerald-100 border-2 border-emerald-200 flex items-center justify-center shrink-0">
                        @if($user->avatar)
                            @if(str_starts_with($user->avatar, 'http'))
                                <img x-show="!photoPreview" src="{{ $user->avatar }}" class="w-full h-full object-cover">
                            @else
                                <img x-show="!photoPreview" src="{{ asset('storage/' . $user->avatar) }}" class="w-full h-full object-cover">
                            @endif
                        @else
                            <svg x-show="!photoPreview" class="w-8 h-8 text-emerald-400" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                            </svg>
                        @endif
                        <img x-show="photoPreview" :src="photoPreview" class="w-full h-full object-cover" style="display:none">
                    </div>
                    <div>
                        <input type="file" name="photo" id="photo" x-ref="photoInput"
                               @change="updatePhotoPreview()" accept="image/jpeg,image/jpg,image/png" class="hidden">
                        <label for="photo"
                               class="inline-flex items-center gap-1.5 px-3 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold cursor-pointer hover:bg-emerald-700 transition-colors duration-200">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" stroke-width="2.2" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            Pilih Foto
                        </label>
                        <p class="text-[11px] text-gray-400 mt-1" x-show="!photoFile">JPG, PNG. Max 2MB</p>
                        <p class="text-[11px] text-emerald-600 mt-1 font-semibold" x-show="photoFile" style="display:none">
                            ✓ <span x-text="photoFile ? photoFile.name : ''"></span>
                        </p>
                    </div>
                    @error('photo')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Nama --}}
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Nama Lengkap</label>
                    <input type="text" name="name" id="name"
                           value="{{ old('name', $user->name) }}"
                           :disabled="!editing" required
                           class="w-full px-4 py-3 text-sm font-semibold rounded-xl border-2 transition-all duration-200 outline-none"
                           :class="editing
                               ? 'border-emerald-300 focus:border-emerald-500 bg-white text-gray-800'
                               : 'border-gray-100 bg-gray-50 text-gray-600 cursor-not-allowed'">
                    @error('name')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Email --}}
                <div>
                    <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Email</label>
                    <input type="email" name="email" id="email"
                           value="{{ old('email', $user->email) }}"
                           :disabled="!editing" required
                           class="w-full px-4 py-3 text-sm font-semibold rounded-xl border-2 transition-all duration-200 outline-none"
                           :class="editing
                               ? 'border-emerald-300 focus:border-emerald-500 bg-white text-gray-800'
                               : 'border-gray-100 bg-gray-50 text-gray-600 cursor-not-allowed'">
                    @error('email')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                </div>

                {{-- Password section (only when editing) --}}
                <div x-show="editing" style="display:none" class="space-y-3">
                    <div class="flex items-center gap-2 px-3 py-2.5 bg-blue-50 rounded-xl border border-blue-100">
                        <svg class="w-4 h-4 text-blue-500 shrink-0" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <p class="text-xs text-blue-700 font-semibold">Kosongkan password jika tidak ingin mengubahnya.</p>
                    </div>

                    {{-- New Password --}}
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Password Baru (Opsional)</label>
                        <div class="relative">
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                   x-model="password" placeholder="Min. 8 karakter"
                                   class="w-full px-4 py-3 pr-11 text-sm font-semibold rounded-xl border-2 border-emerald-300 focus:border-emerald-500 bg-white outline-none transition-all duration-200">
                            <button type="button" @click="showPassword = !showPassword"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')<p class="text-red-500 text-xs mt-1">{{ $message }}</p>@enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-xs font-black text-gray-500 uppercase tracking-widest mb-1.5">Konfirmasi Password</label>
                        <div class="relative">
                            <input :type="showPasswordConfirm ? 'text' : 'password'" name="password_confirmation"
                                   x-model="passwordConfirm" placeholder="Ulangi password baru"
                                   class="w-full px-4 py-3 pr-11 text-sm font-semibold rounded-xl border-2 border-emerald-300 focus:border-emerald-500 bg-white outline-none transition-all duration-200">
                            <button type="button" @click="showPasswordConfirm = !showPasswordConfirm"
                                    class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 hover:text-gray-600">
                                <svg x-show="!showPasswordConfirm" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPasswordConfirm" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24" style="display:none">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        {{-- Match indicator --}}
                        <p x-show="password.length > 0 && passwordConfirm.length > 0"
                           :class="password === passwordConfirm ? 'text-emerald-600' : 'text-red-500'"
                           class="text-xs mt-1 font-semibold flex items-center gap-1" style="display:none">
                            <span x-text="password === passwordConfirm ? '✓ Password cocok' : '✗ Password tidak cocok'"></span>
                        </p>
                    </div>
                </div>

                {{-- Save Button --}}
                <div x-show="editing" style="display:none">
                    <button type="submit"
                            class="w-full py-3.5 rounded-2xl text-sm font-black text-white bg-emerald-600 hover:bg-emerald-700 shadow-md shadow-emerald-300/40 transition-all duration-200 flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                        </svg>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- ── STATISTIK BELAJAR ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4">
            <h2 class="text-base font-black text-gray-800 mb-4">Statistik Belajar</h2>
            <div class="space-y-4">
                @foreach([
                    ['label' => 'Materi Belajar',   'done' => $completedMateri, 'total' => $totalMateri,   'from' => 'from-emerald-500', 'to' => 'to-emerald-600', 'text' => 'text-emerald-600'],
                    ['label' => 'Kuis',              'done' => $completedKuis,   'total' => $totalKuis,     'from' => 'from-blue-500',    'to' => 'to-blue-600',    'text' => 'text-blue-600'],
                    ['label' => 'Tugas',             'done' => $completedTugas,  'total' => $totalTugas,    'from' => 'from-violet-500',  'to' => 'to-violet-600',  'text' => 'text-violet-600'],
                ] as $stat)
                    <div>
                        <div class="flex justify-between items-center mb-1.5">
                            <span class="text-sm font-semibold text-gray-700">{{ $stat['label'] }}</span>
                            <span class="text-xs font-black {{ $stat['text'] }}">{{ $stat['done'] }}/{{ $stat['total'] }}</span>
                        </div>
                        <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                            <div class="h-full bg-gradient-to-r {{ $stat['from'] }} {{ $stat['to'] }} rounded-full transition-all duration-700"
                                 style="width: {{ $stat['total'] > 0 ? ($stat['done'] / $stat['total']) * 100 : 0 }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- ── AKTIVITAS TERAKHIR ── --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4">
            <h2 class="text-base font-black text-gray-800 mb-4">Aktivitas Terakhir</h2>
            @if($recentProgress->count() > 0)
                <div class="space-y-2">
                    @foreach($recentProgress as $progress)
                        <div class="flex items-center gap-3 p-3 bg-emerald-50/60 rounded-xl border border-emerald-100/60">
                            <div class="w-8 h-8 bg-emerald-100 rounded-full flex items-center justify-center shrink-0">
                                <svg class="w-4 h-4 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-semibold text-gray-800 truncate">{{ $progress->materi->judul_materi ?? 'Materi' }}</p>
                                <p class="text-xs text-gray-400">{{ $progress->updated_at ? $progress->updated_at->diffForHumans() : '-' }}</p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-300">
                    <div class="text-4xl mb-2">📭</div>
                    <p class="text-sm font-semibold">Belum ada aktivitas</p>
                </div>
            @endif
        </div>

        {{-- ── QUICK LINKS ── --}}
        <div class="grid grid-cols-3 gap-3">
            @foreach([
                ['href' => route('siswa.materi.index'), 'emoji' => '📖', 'label' => 'Materi',  'sub' => 'Lanjutkan belajar', 'color' => 'bg-emerald-50 border-emerald-100'],
                ['href' => route('siswa.kuis.index'),   'emoji' => '✏️', 'label' => 'Kuis',    'sub' => 'Uji pemahaman',     'color' => 'bg-blue-50 border-blue-100'],
                ['href' => route('siswa.tugas.index'),  'emoji' => '📋', 'label' => 'Tugas',   'sub' => 'Kumpulkan tugas',   'color' => 'bg-violet-50 border-violet-100'],
            ] as $link)
                <a href="{{ $link['href'] }}"
                   class="flex flex-col items-center text-center p-4 rounded-2xl border {{ $link['color'] }} hover:shadow-md transition-all duration-200 active:scale-95">
                    <span class="text-2xl mb-1.5">{{ $link['emoji'] }}</span>
                    <span class="text-xs font-black text-gray-700">{{ $link['label'] }}</span>
                    <span class="text-[10px] text-gray-400 font-semibold leading-tight mt-0.5">{{ $link['sub'] }}</span>
                </a>
            @endforeach
        </div>

    </div>
</div>
@endsection
