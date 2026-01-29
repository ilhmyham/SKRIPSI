@extends('layouts.app')

@section('title', $kuis->judul_kuis)

@section('content')
<div class="min-h-screen" x-data="quizApp()">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Quiz Start Screen -->
        <div x-show="!quizStarted" class="card text-center">
            <h1 class="text-4xl font-bold mb-4" style="color: var(--color-primary);">{{ $kuis->judul_kuis }}</h1>
            
            @if($kuis->deskripsi)
                <p class="text-lg mb-6" style="color: var(--color-text-secondary);">{{ $kuis->deskripsi }}</p>
            @endif

            <div class="flex items-center justify-center gap-8 mb-8">
                <div class="text-center">
                    <div class="text-5xl font-bold mb-2" style="color: var(--color-primary);">{{ $kuis->pertanyaan->count() }}</div>
                    <p style="color: var(--color-text-secondary);">Pertanyaan</p>
                </div>
            </div>

            <button @click="startQuiz" class="btn btn-primary text-2xl py-6 px-12">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
                Mulai Kuis
            </button>
        </div>

        <!-- Quiz Questions -->
        <div x-show="quizStarted && !quizFinished">
            <!-- Progress Bar -->
            <div class="mb-6">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-lg font-semibold">Pertanyaan <span x-text="currentQuestion + 1"></span> dari {{ $kuis->pertanyaan->count() }}</span>
                    <span class="text-lg" style="color: var(--color-text-secondary);" x-text="Math.round(((currentQuestion + 1) / {{ $kuis->pertanyaan->count() }}) * 100) + '%'"></span>
                </div>
                <div class="progress-bar">
                    <div class="progress-bar-fill" :style="`width: ${((currentQuestion + 1) / {{ $kuis->pertanyaan->count() }}) * 100}%`"></div>
                </div>
            </div>

            <!-- Questions -->
            @foreach($kuis->pertanyaan as $index => $pertanyaan)
                <div x-show="currentQuestion === {{ $index }}" class="card">
                    <h2 class="text-2xl font-bold mb-6">{{ $pertanyaan->text_pertanyaan }}</h2>

                    <div class="space-y-4">
                        @foreach($pertanyaan->opsiJawaban as $opsi)
                            <button 
                                @click="selectAnswer({{ $pertanyaan->pertanyaan_id }}, {{ $opsi->opsi_id }})"
                                :class="answers[{{ $pertanyaan->pertanyaan_id }}] === {{ $opsi->opsi_id }} ? 'bg-green-100 border-green-500' : 'bg-white border-gray-300'"
                                class="w-full text-left px-6 py-4 text-lg border-2 rounded-lg hover:border-green-400 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-6 h-6 rounded-full border-2 flex items-center justify-center"
                                         :class="answers[{{ $pertanyaan->pertanyaan_id }}] === {{ $opsi->opsi_id }} ? 'border-green-500 bg-green-500' : 'border-gray-400'">
                                        <svg x-show="answers[{{ $pertanyaan->pertanyaan_id }}] === {{ $opsi->opsi_id }}" 
                                             class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                        </svg>
                                    </div>
                                    <span>{{ $opsi->teks_opsi }}</span>
                                </div>
                            </button>
                        @endforeach
                    </div>

                    <!-- Navigation -->
                    <div class="flex gap-4 mt-8">
                        <button 
                            x-show="currentQuestion > 0"
                            @click="currentQuestion--"
                            class="btn btn-secondary flex-1">
                            Sebelumnya
                        </button>
                        <button 
                            x-show="currentQuestion < {{ $kuis->pertanyaan->count() - 1 }}"
                            @click="currentQuestion++"
                            class="btn btn-primary flex-1">
                            Selanjutnya
                        </button>
                        <button 
                            x-show="currentQuestion === {{ $kuis->pertanyaan->count() - 1 }}"
                            @click="submitQuiz"
                            class="btn btn-primary flex-1">
                            Selesai
                        </button>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Hidden form for submission -->
        <form id="quizForm" method="POST" action="{{ route('siswa.kuis.submit', $kuis) }}" style="display: none;">
            @csrf
            <input type="hidden" name="answers" x-model="JSON.stringify(answers)">
        </form>
    </div>
</div>

<script>
function quizApp() {
    return {
        quizStarted: false,
        quizFinished: false,
        currentQuestion: 0,
        answers: {},
        
        startQuiz() {
            this.quizStarted = true;
        },
        
        selectAnswer(questionId, optionId) {
            this.answers[questionId] = optionId;
        },
        
        submitQuiz() {
            if (Object.keys(this.answers).length < {{ $kuis->pertanyaan->count() }}) {
                if (!confirm('Masih ada pertanyaan yang belum dijawab. Lanjutkan submit?')) {
                    return;
                }
            }
            
            // Convert answers object to format expected by backend
            const formData = new FormData();
            formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);
            
            for (const [questionId, optionId] of Object.entries(this.answers)) {
                formData.append(`answers[${questionId}]`, optionId);
            }
            
            fetch('{{ route('siswa.kuis.submit', $kuis) }}', {
                method: 'POST',
                body: formData
            }).then(response => {
                if (response.redirected) {
                    window.location.href = response.url;
                }
            });
        }
    }
}
</script>
@endsection
