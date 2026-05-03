@props([
    'label' => 'Progress Belajar Kamu',
    'percentage' => 0,
    'completedCount' => 0,
    'totalCount' => 0,
    'showStats' => true
])

<div class="bg-white/30 backdrop-blur-sm rounded-2xl p-6 border border-white/40">
    <div class="flex items-center justify-between mb-3">
        <h3 class="text-lg font-bold text-white">{{ $label }}</h3>
        <span class="text-2xl md:text-3xl font-bold text-white">{{ number_format($percentage, 0) }}%</span>
    </div>
    
    <!-- Progress Bar -->
    <div class="h-4 bg-white/20 rounded-full overflow-hidden mb-3">
        <div class="h-full bg-gradient-to-r from-yellow-400 to-yellow-300 rounded-full transition-all duration-500 ease-out"
             style="width: {{ $percentage }}%"></div>
    </div>
    
    @if($showStats)
        <div class="flex items-center justify-between text-sm text-white/90">
            <span>{{ $completedCount }} Modul Selesai</span>
            <span>{{ $totalCount }} Total Modul</span>
        </div>
    @endif
</div>
