@props([
    'title' => 'Selamat Datang!',
    'subtitle' => null,
    'gradient' => true
])

<div class="hero-gradient {{ $gradient ? 'bg-gradient-to-br from-emerald-600 to-emerald-800' : '' }} rounded-3xl px-6 py-12 md:py-16 mb-8 text-white relative overflow-hidden">
    <!-- Decorative Background Pattern -->
    <div class="absolute inset-0 opacity-10">
        <div class="absolute top-10 right-10 w-64 h-64 bg-white rounded-full blur-3xl"></div>
        <div class="absolute bottom-10 left-10 w-48 h-48 bg-white rounded-full blur-3xl"></div>
    </div>
    
    <!-- Content -->
    <div class="relative z-10 text-center max-w-4xl mx-auto">
        <h1 class="text-3xl md:text-5xl font-bold mb-3 text-shadow-lg">
            {{ $title }}
        </h1>
        
        @if($subtitle)
            <p class="text-lg md:text-xl text-white/90">
                {{ $subtitle }}
            </p>
        @endif
        
        {{ $slot }}
    </div>
</div>

<style>
.text-shadow-lg {
    text-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
