@extends('layouts.app')

@section('title', 'Profile Saya')

@section('content')
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 md:py-8">
    
    <!-- Profile Header -->
    <div class="bg-gradient-to-br from-emerald-600 to-emerald-800 rounded-3xl px-6 py-8 md:py-12 mb-6 md:mb-8 text-white relative overflow-hidden shadow-xl">
        <!-- Decorative Background -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-10 right-10 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-10 left-10 w-48 h-48 bg-white rounded-full blur-3xl"></div>
        </div>
        
        <div class="relative z-10 flex flex-col md:flex-row items-center gap-6">
            <!-- Avatar -->
            <div class="w-24 h-24 md:w-32 md:h-32 bg-white rounded-full flex items-center justify-center shadow-2xl overflow-hidden">
                @if($user->avatar)
                    @if(str_starts_with($user->avatar, 'http'))
                        <!-- Google OAuth Avatar -->
                        <img src="{{ $user->avatar }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @else
                        <!-- Uploaded Avatar -->
                        <img src="{{ asset('storage/' . $user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                    @endif
                @else
                    <!-- Default Avatar Icon -->
                    <svg class="w-12 h-12 md:w-16 md:h-16 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                    </svg>
                @endif
            </div>
            
            <!-- User Info -->
            <div class="flex-1 text-center md:text-left">
                <h1 class="text-2xl md:text-4xl font-bold mb-2">{{ $user->name }}</h1>
                <p class="text-white/90 text-base md:text-lg mb-1">{{ $user->email }}</p>
                <span class="inline-block px-4 py-1 bg-white/20 backdrop-blur-sm rounded-full text-sm font-semibold">
                    Siswa
                </span>
            </div>
            
            <!-- Logout Button -->
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="btn bg-white/20 hover:bg-white/30 text-white border-2 border-white/40">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                    </svg>
                    Keluar
                </button>
            </form>
        </div>
    </div>

    <!-- Statistics Grid -->
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-6 mb-6 md:mb-8">
        <!-- Overall Progress -->
        <div class="card bg-white rounded-2xl shadow-lg p-4 md:p-6 text-center col-span-2">
            <div class="text-4xl md:text-5xl font-bold mb-2" style="color: var(--color-primary);">
                {{ number_format($overallProgress, 0) }}%
            </div>
            <p class="text-sm md:text-base text-gray-600">Progress Keseluruhan</p>
        </div>
        
        <!-- Materi Completed -->
        <div class="card bg-white rounded-2xl shadow-lg p-4 md:p-6 text-center">
            <div class="text-3xl md:text-4xl font-bold mb-2 text-emerald-600">
                {{ $completedMateri }}/{{ $totalMateri }}
            </div>
            <p class="text-xs md:text-sm text-gray-600">Materi Selesai</p>
        </div>
        
        <!-- Kuis Completed -->
        <div class="card bg-white rounded-2xl shadow-lg p-4 md:p-6 text-center">
            <div class="text-3xl md:text-4xl font-bold mb-2 text-blue-600">
                {{ $completedKuis }}/{{ $totalKuis }}
            </div>
            <p class="text-xs md:text-sm text-gray-600">Kuis Dikerjakan</p>
        </div>
    </div>

    <!-- Profile Information Card -->
    <div class="mb-6 md:mb-8" x-data="{ 
        editing: false,
        showPassword: false,
        showPasswordConfirmation: false,
        password: '',
        passwordConfirmation: ''
    }">
        <div class="card bg-white rounded-2xl shadow-lg">
            <div class="flex justify-between items-center mb-6">
                <h2 class="text-xl md:text-2xl font-bold text-gray-800">Informasi Profile</h2>
                
                <!-- Edit/Cancel Buttons -->
                <div class="flex gap-2">
                    <button 
                        @click="editing = !editing; if(!editing) { password = ''; passwordConfirmation = ''; }"
                        type="button"
                        class="btn text-sm md:text-base"
                        :class="editing ? 'btn-secondary' : 'btn-primary'"
                    >
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="!editing">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                        </svg>
                        <svg class="w-4 h-4 md:w-5 md:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" x-show="editing" style="display: none;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                        <span x-text="editing ? 'Batal' : 'Edit'"></span>
                    </button>
                </div>
            </div>

            <!-- Profile Form -->
            <form method="POST" action="{{ route('siswa.profile.update') }}" class="space-y-5" enctype="multipart/form-data" x-data="{
                photoPreview: null,
                photoFile: null,
                updatePhotoPreview() {
                    const file = this.$refs.photoInput.files[0];
                    if (file) {
                        this.photoFile = file;
                        const reader = new FileReader();
                        reader.onload = (e) => {
                            this.photoPreview = e.target.result;
                        };
                        reader.readAsDataURL(file);
                    }
                }
            }">
                @csrf
                @method('PUT')

                <!-- Photo Upload Field -->
                <div x-show="editing" style="display: none;">
                    <label class="block text-base font-semibold mb-2 text-gray-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"/>
                            </svg>
                            <span>Foto Profile</span>
                        </div>
                    </label>
                    
                    <!-- Current/Preview Photo -->
                    <div class="mb-3 flex items-center gap-4">
                        <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center overflow-hidden border-4 border-gray-200">
                            @if($user->avatar)
                                @if(str_starts_with($user->avatar, 'http'))
                                    <img x-show="!photoPreview" src="{{ $user->avatar }}" alt="Current photo" class="w-full h-full object-cover">
                                @else
                                    <img x-show="!photoPreview" src="{{ asset('storage/' . $user->avatar) }}" alt="Current photo" class="w-full h-full object-cover">
                                @endif
                            @else
                                <svg x-show="!photoPreview" class="w-12 h-12 text-gray-400" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd"/>
                                </svg>
                            @endif
                            <!-- Preview -->
                            <img x-show="photoPreview" :src="photoPreview" alt="Preview" class="w-full h-full object-cover" style="display: none;">
                        </div>
                        
                        <div class="flex-1">
                            <input 
                                type="file" 
                                name="photo" 
                                id="photo"
                                x-ref="photoInput"
                                @change="updatePhotoPreview()"
                                accept="image/jpeg,image/jpg,image/png"
                                class="hidden"
                            >
                            <label for="photo" class="btn btn-secondary cursor-pointer inline-flex">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>Pilih Foto</span>
                            </label>
                            <p class="text-xs text-gray-500 mt-2">JPG, PNG. Max 2MB</p>
                            <p x-show="photoFile" class="text-sm text-green-600 mt-1" style="display: none;">
                                ✓ <span x-text="photoFile ? photoFile.name : ''"></span>
                            </p>
                        </div>
                    </div>
                    @error('photo')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Name Field -->
                <div>
                    <label for="name" class="block text-base font-semibold mb-2 text-gray-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Nama Lengkap</span>
                        </div>
                    </label>
                    <input 
                        type="text" 
                        name="name" 
                        id="name" 
                        value="{{ old('name', $user->name) }}"
                        :disabled="!editing"
                        required
                        class="w-full px-4 py-3 text-base border-2 rounded-xl transition"
                        :class="editing ? 'border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 bg-white' : 'border-gray-200 bg-gray-50 cursor-not-allowed'"
                    >
                    @error('name')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Email Field -->
                <div>
                    <label for="email" class="block text-base font-semibold mb-2 text-gray-700">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span>Email</span>
                        </div>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email', $user->email) }}"
                        :disabled="!editing"
                        required
                        class="w-full px-4 py-3 text-base border-2 rounded-xl transition"
                        :class="editing ? 'border-gray-300 focus:border-green-500 focus:ring-2 focus:ring-green-200 bg-white' : 'border-gray-200 bg-gray-50 cursor-not-allowed'"
                    >
                    @error('email')
                        <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Password Fields (only shown when editing) -->
                <div x-show="editing" style="display: none;">
                    <div class="p-4 bg-blue-50 border-2 border-blue-200 rounded-xl mb-4">
                        <p class="text-sm text-blue-800">
                            <strong>Info:</strong> Kosongkan password jika tidak ingin mengubahnya.
                        </p>
                    </div>

                    <!-- New Password -->
                    <div class="mb-4">
                        <label for="password" class="block text-base font-semibold mb-2 text-gray-700">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                                </svg>
                                <span>Password Baru (Opsional)</span>
                            </div>
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPassword ? 'text' : 'password'"
                                name="password" 
                                id="password"
                                x-model="password"
                                class="w-full px-4 py-3 pr-12 text-base border-2 border-gray-300 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                                placeholder="Min. 8 karakter"
                            >
                            <button 
                                type="button"
                                @click="showPassword = !showPassword"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                            >
                                <svg x-show="!showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="text-red-600 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-base font-semibold mb-2 text-gray-700">
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <span>Konfirmasi Password Baru</span>
                            </div>
                        </label>
                        <div class="relative">
                            <input 
                                :type="showPasswordConfirmation ? 'text' : 'password'"
                                name="password_confirmation" 
                                id="password_confirmation"
                                x-model="passwordConfirmation"
                                class="w-full px-4 py-3 pr-12 text-base border-2 border-gray-300 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                                placeholder="Ulangi password baru"
                            >
                            <button 
                                type="button"
                                @click="showPasswordConfirmation = !showPasswordConfirmation"
                                class="absolute right-3 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700"
                            >
                                <svg x-show="!showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                                <svg x-show="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                </svg>
                            </button>
                        </div>
                        <!-- Password Match Indicator -->
                        <p x-show="password.length > 0 && passwordConfirmation.length > 0" 
                           :class="password === passwordConfirmation ? 'text-green-600' : 'text-red-600'"
                           class="text-sm mt-1 flex items-center gap-1"
                           style="display: none;">
                            <svg x-show="password === passwordConfirmation" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                            <svg x-show="password !== passwordConfirmation" class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20" style="display: none;">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                            </svg>
                            <span x-text="password === passwordConfirmation ? 'Password cocok' : 'Password tidak cocok'"></span>
                        </p>
                    </div>
                </div>

                <!-- Save Button (only shown when editing) -->
                <div x-show="editing" style="display: none;">
                    <button type="submit" class="btn btn-primary w-full text-lg py-4">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        <span>Simpan Perubahan</span>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Learning Stats -->
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <!-- Progress Details -->
        <div class="card bg-white rounded-2xl shadow-lg">
            <h2 class="text-xl md:text-2xl font-bold mb-6 text-gray-800">Statistik Belajar</h2>
            
            <div class="space-y-4">
                <!-- Materi Progress -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold text-gray-700">Materi Belajar</span>
                        <span class="text-sm font-bold text-emerald-600">
                            {{ $completedMateri }}/{{ $totalMateri }}
                        </span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-emerald-500 to-emerald-600 rounded-full transition-all duration-500"
                             style="width: {{ $totalMateri > 0 ? ($completedMateri / $totalMateri) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <!-- Kuis Progress -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold text-gray-700">Kuis</span>
                        <span class="text-sm font-bold text-blue-600">
                            {{ $completedKuis }}/{{ $totalKuis }}
                        </span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-blue-500 to-blue-600 rounded-full transition-all duration-500"
                             style="width: {{ $totalKuis > 0 ? ($completedKuis / $totalKuis) * 100 : 0 }}%"></div>
                    </div>
                </div>
                
                <!-- Tugas Progress -->
                <div>
                    <div class="flex justify-between items-center mb-2">
                        <span class="font-semibold text-gray-700">Tugas</span>
                        <span class="text-sm font-bold text-purple-600">
                            {{ $completedTugas }}/{{ $totalTugas }}
                        </span>
                    </div>
                    <div class="h-3 bg-gray-200 rounded-full overflow-hidden">
                        <div class="h-full bg-gradient-to-r from-purple-500 to-purple-600 rounded-full transition-all duration-500"
                             style="width: {{ $totalTugas > 0 ? ($completedTugas / $totalTugas) * 100 : 0 }}%"></div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="card bg-white rounded-2xl shadow-lg">
            <h2 class="text-xl md:text-2xl font-bold mb-6 text-gray-800">Aktivitas Terakhir</h2>
            
            @if($recentProgress->count() > 0)
                <div class="space-y-3">
                    @foreach($recentProgress as $progress)
                        <div class="flex items-start gap-3 p-3 bg-emerald-50 rounded-lg">
                            <div class="w-10 h-10 bg-emerald-100 rounded-full flex items-center justify-center flex-shrink-0">
                                <svg class="w-5 h-5 text-emerald-600" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-800 line-clamp-1">
                                    {{ $progress->materi->judul_materi ?? 'Materi' }}
                                </p>
                                <p class="text-sm text-gray-600">
                                    {{ $progress->tanggal_update ? $progress->tanggal_update->diffForHumans() : '-' }}
                                </p>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <div class="text-center py-8 text-gray-400">
                    <svg class="w-16 h-16 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <p>Belum ada aktivitas</p>
                </div>
            @endif
        </div>
    </div>

    <!-- Action Buttons -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 md:gap-6">
        <a href="{{ route('siswa.materi.index') }}" 
           class="card card-interactive bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-2xl transition-all">
            <div class="w-16 h-16 bg-emerald-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-2">Belajar Materi</h3>
            <p class="text-sm text-gray-600">Lanjutkan belajar</p>
        </a>
        
        <a href="{{ route('siswa.kuis.index') }}" 
           class="card card-interactive bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-2xl transition-all">
            <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-2">Kerjakan Kuis</h3>
            <p class="text-sm text-gray-600">Uji pemahamanmu</p>
        </a>
        
        <a href="{{ route('siswa.tugas.index') }}" 
           class="card card-interactive bg-white rounded-2xl shadow-lg p-6 text-center hover:shadow-2xl transition-all">
            <div class="w-16 h-16 bg-purple-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
            </div>
            <h3 class="font-bold text-lg mb-2">Lihat Tugas</h3>
            <p class="text-sm text-gray-600">Kumpulkan tugasmu</p>
        </a>
    </div>

</div>
@endsection
