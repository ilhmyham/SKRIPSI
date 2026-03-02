@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 py-12">
    <div class="max-w-md w-full">
        <!-- Login Card -->
        <div class="card">
            <!-- Header -->
            <div class="relative text-center mb-4">
                <a href="{{ url('/') }}" 
   class="absolute left-4 top-4 flex items-center justify-center w-10 h-10 bg-gray-100 rounded-full text-gray-500 hover:bg-green-100 hover:text-green-600 transition-all duration-300 shadow-sm" 
   title="Kembali ke Beranda">
    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
    </svg>
</a>
                <div class="flex justify-center mb-0">
                    <img src="{{ asset('images/logo.png') }}" alt="Ayat Isyarat" class="h-24 w-24 object-contain">
                </div>
                <h1 class="text-3xl font-bold" style="color: var(--color-primary);">Ayat Isyarat</h1>
                <p class="text-lg mt-2" style="color: var(--color-text-secondary);">Tunarungu & Tunawicara</p>
            </div>

            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 p-4 bg-red-50 border-2 border-red-300 rounded-lg">
                    <div class="flex items-start gap-3">
                        <svg class="w-6 h-6 text-red-600 flex-shrink-0 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
                        </svg>
                        <div class="flex-1 text-red-800">
                            @foreach ($errors->all() as $error)
                                <p class="text-base">{{ $error }}</p>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            <!-- Login Form -->
            <form method="POST" action="{{ route('login') }}" class="space-y-6" x-data="{ showPassword: false }">
                @csrf

                <!-- Email Input -->
                <div>
                    <label for="email" class="block text-lg font-semibold mb-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"/>
                            </svg>
                            <span>Email</span>
                        </div>
                    </label>
                    <input 
                        type="email" 
                        name="email" 
                        id="email" 
                        value="{{ old('email') }}"
                        required 
                        autofocus
                        class="w-full px-6 py-4 text-lg border-2 border-gray-300 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                        placeholder="nama@email.com"
                        style="min-height: 60px;"
                    >
                </div>

                <!-- Password Input -->
                <div>
                    <label for="password" class="block text-lg font-semibold mb-2">
                        <div class="flex items-center gap-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            <span>Password</span>
                        </div>
                    </label>
                    <div class="relative">
                        <input 
                            :type="showPassword ? 'text' : 'password'"
                            name="password" 
                            id="password" 
                            required
                            class="w-full px-6 py-4 pr-12 text-lg border-2 border-gray-300 rounded-xl focus:border-green-500 focus:ring-2 focus:ring-green-200 transition"
                            placeholder="••••••••"
                            style="min-height: 60px;"
                        >
                        <button 
                            type="button"
                            @click="showPassword = !showPassword"
                            class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500 hover:text-gray-700 focus:outline-none"
                        >
                            <svg x-show="!showPassword" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPassword" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    {{-- Lupa Password --}}
                    <div class="mt-2 text-right">
                        <a href="https://wa.me/6285172112525?text=Halo%20Admin,%20saya%20lupa%20password%20akun%20AyatIsyarat%20saya.%20Mohon%20bantuannya!" 
                           target="_blank" 
                           class="text-sm font-semibold text-gray-500 hover:text-emerald-600 transition-colors">
                           Lupa password? Hubungi Admin
                        </a>
                    </div>
                </div>

                <!-- Remember Me -->
                <div class="flex items-center">
                    <input 
                        type="checkbox" 
                        name="remember" 
                        id="remember"
                        class="w-6 h-6 text-green-600 border-2 border-gray-300 rounded focus:ring-2 focus:ring-green-200"
                    >
                    <label for="remember" class="ml-3 text-base font-medium">
                        Ingat saya
                    </label>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="btn btn-primary w-full text-xl py-5">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"/>
                    </svg>
                    <span>Masuk</span>
                </button>
            </form>
                    
            <!-- Registration Link -->
            <div class="mt-6 text-center pt-6 border-t-2 border-gray-200">
                <p class="text-base" style="color: var(--color-text-secondary);">
                    Belum punya akun? 
                    <a href="{{ route('register') }}" class="font-semibold hover:underline" style="color: var(--color-primary);">
                        Daftar di sini
                    </a>
                </p>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="//unpkg.com/alpinejs" defer></script>
@endpush
@endsection
