@extends('layouts.siswa')

@section('title', $kuis->judul_kuis)

@section('content')
<div class="pb-24 min-h-screen bg-gray-50" x-data="quizApp()">

    {{-- ── HERO HEADER ── --}}
    <div class="bg-gradient-to-br from-emerald-600 via-emerald-700 to-emerald-900 pt-10 pb-20 px-5 relative overflow-hidden">
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-4 right-4 w-64 h-64 bg-white rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-48 h-48 bg-amber-300 rounded-full blur-3xl"></div>
        </div>
        <div class="max-w-2xl mx-auto relative z-10">
            <a href="{{ route('siswa.kuis.index') }}" class="inline-flex items-center gap-1.5 text-emerald-200 hover:text-white text-sm font-semibold mb-4 transition-colors">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Kembali ke Daftar Kuis
            </a>
            <p class="text-emerald-200 text-xs font-bold uppercase tracking-widest mb-1">Kuis Interaktif</p>
            <h1 class="text-white text-2xl sm:text-3xl font-black tracking-tight leading-tight">{{ $kuis->judul_kuis }}</h1>
            @if($kuis->deskripsi)
                <p class="text-emerald-200/80 text-sm mt-2">{{ $kuis->deskripsi }}</p>
            @endif
        </div>
    </div>

    <div class="max-w-2xl mx-auto px-4 -mt-10 relative z-10">

        {{-- ── SCREEN: MULAI KUIS ── --}}
        <div x-show="!quizStarted" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0">
            <div class="bg-white rounded-3xl shadow-xl border border-gray-100 p-8 text-center">

                {{-- Stat --}}
                <div class="inline-flex items-center justify-center w-20 h-20 rounded-2xl bg-emerald-50 mb-6">
                    <svg class="w-10 h-10 text-emerald-600" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>

                <div class="flex justify-center gap-8 mb-8">
                    <div class="text-center">
                        <div class="text-4xl font-black text-emerald-600">{{ $kuis->questions->count() }}</div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-1">Pertanyaan</p>
                    </div>
                    <div class="w-px bg-gray-200"></div>
                    <div class="text-center">
                        <div class="text-4xl font-black text-emerald-600">–</div>
                        <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mt-1">Waktu</p>
                    </div>
                </div>

                <p class="text-sm text-gray-400 mb-6">Baca setiap pertanyaan dengan teliti sebelum menjawab.</p>

                <button @click="startQuiz"
                        class="w-full flex items-center justify-center gap-3 bg-emerald-600 hover:bg-emerald-700 active:scale-[0.98] text-white font-black text-lg py-4 px-8 rounded-2xl shadow-lg shadow-emerald-200 transition-all duration-200">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.348a1.125 1.125 0 010 1.971l-11.54 6.347a1.125 1.125 0 01-1.667-.985V5.653z"/>
                    </svg>
                    Mulai Kuis
                </button>
            </div>
        </div>

        {{-- ── SCREEN: SOAL ── --}}
        <div x-show="quizStarted && !quizFinished" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100">

            {{-- Progress --}}
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 px-5 py-4 mb-4">
                <div class="flex justify-between items-center mb-2">
                    <span class="text-sm font-bold text-gray-700">
                        Soal <span class="text-emerald-600" x-text="currentQuestion + 1"></span>
                        <span class="text-gray-400 font-medium">/ {{ $kuis->questions->count() }}</span>
                    </span>
                    <span class="text-xs font-black text-emerald-600 bg-emerald-50 px-2.5 py-1 rounded-full"
                          x-text="Math.round(((currentQuestion + 1) / {{ $kuis->questions->count() }}) * 100) + '%'">
                    </span>
                </div>
                <div class="w-full bg-gray-100 rounded-full h-2">
                    <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 h-2 rounded-full transition-all duration-500"
                         :style="`width: ${((currentQuestion + 1) / {{ $kuis->questions->count() }}) * 100}%`">
                    </div>
                </div>
            </div>

            {{-- Question Cards --}}
            @foreach($kuis->questions as $index => $pertanyaan)
            <div x-show="currentQuestion === {{ $index }}"
                 x-transition:enter="transition ease-out duration-200"
                 x-transition:enter-start="opacity-0 translate-x-4"
                 x-transition:enter-end="opacity-100 translate-x-0"
                 class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">

                {{-- Question header --}}
                <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border-b border-gray-100 px-6 py-5">
                    <div class="flex items-start gap-4">
                        <div class="flex-shrink-0 w-8 h-8 rounded-full bg-emerald-600 text-white text-sm font-black flex items-center justify-center shadow-sm">
                            {{ $index + 1 }}
                        </div>
                        <h2 class="text-base sm:text-lg font-bold text-gray-800 leading-snug pt-0.5">
                            {{ $pertanyaan->text_pertanyaan }}
                        </h2>
                    </div>
                </div>

                <div class="p-5">
                    {{-- Gambar pertanyaan --}}
                    @if($pertanyaan->gambar_pertanyaan)
                    <div class="mb-5 flex justify-center">
                        <img src="{{ asset('storage/' . $pertanyaan->gambar_pertanyaan) }}"
                             alt="Gambar soal"
                             class="max-h-56 w-auto rounded-2xl border border-gray-200 object-contain shadow-sm">
                    </div>
                    @endif

                    {{-- Opsi --}}
                    <div class="space-y-3">
                        @php $optionLabels = ['A','B','C','D','E']; @endphp
                        @foreach($pertanyaan->options as $oIdx => $opsi)
                        <button
                            @click="selectAnswer({{ $pertanyaan->id }}, {{ $opsi->id }})"
                            :class="answers[{{ $pertanyaan->id }}] === {{ $opsi->id }}
                                ? 'border-emerald-500 bg-emerald-50 shadow-md shadow-emerald-100'
                                : 'border-gray-200 bg-white hover:border-emerald-300 hover:bg-emerald-50/50'"
                            class="w-full text-left flex items-center gap-4 px-4 py-3.5 border-2 rounded-2xl transition-all duration-200 group">

                            {{-- Label huruf --}}
                            <div class="flex-shrink-0 w-9 h-9 rounded-xl flex items-center justify-center text-sm font-black transition-all duration-200"
                                 :class="answers[{{ $pertanyaan->id }}] === {{ $opsi->id }}
                                     ? 'bg-emerald-600 text-white'
                                     : 'bg-gray-100 text-gray-500 group-hover:bg-emerald-100 group-hover:text-emerald-700'">
                                {{ $optionLabels[$oIdx] ?? ($oIdx+1) }}
                            </div>

                            {{-- Konten opsi --}}
                            @if($opsi->gambar_opsi)
                                <div class="flex flex-col gap-1.5 flex-1">
                                    <img src="{{ asset('storage/' . $opsi->gambar_opsi) }}"
                                         alt="Opsi {{ $optionLabels[$oIdx] ?? ($oIdx+1) }}"
                                         class="max-h-28 rounded-xl object-contain">
                                    @if($opsi->teks_opsi)
                                        <span class="text-sm text-gray-600">{{ $opsi->teks_opsi }}</span>
                                    @endif
                                </div>
                            @else
                                <span class="text-sm sm:text-base font-semibold text-gray-700 flex-1"
                                      :class="answers[{{ $pertanyaan->id }}] === {{ $opsi->id }} ? 'text-emerald-800' : ''">
                                    {{ $opsi->teks_opsi }}
                                </span>
                            @endif

                            {{-- Checkmark --}}
                            <div x-show="answers[{{ $pertanyaan->id }}] === {{ $opsi->id }}"
                                 class="flex-shrink-0 w-5 h-5 rounded-full bg-emerald-600 flex items-center justify-center">
                                <svg class="w-3 h-3 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                        </button>
                        @endforeach
                    </div>

                    {{-- Navigasi --}}
                    <div class="flex gap-3 mt-6">
                        <button x-show="currentQuestion > 0"
                                @click="currentQuestion--"
                                class="flex-1 flex items-center justify-center gap-2 py-3.5 px-4 rounded-2xl border-2 border-gray-200 bg-white text-gray-600 font-bold text-sm hover:border-gray-300 hover:bg-gray-50 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                            </svg>
                            Sebelumnya
                        </button>

                        <button x-show="currentQuestion < {{ $kuis->questions->count() - 1 }}"
                                @click="currentQuestion++"
                                class="flex-1 flex items-center justify-center gap-2 py-3.5 px-4 rounded-2xl bg-emerald-600 hover:bg-emerald-700 text-white font-bold text-sm shadow-sm shadow-emerald-200 transition-all duration-200">
                            Selanjutnya
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 5l7 7-7 7"/>
                            </svg>
                        </button>

                        <button x-show="currentQuestion === {{ $kuis->questions->count() - 1 }}"
                                @click="submitQuiz"
                                class="flex-1 flex items-center justify-center gap-2 py-3.5 px-4 rounded-2xl bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white font-black text-sm shadow-lg shadow-emerald-200 transition-all duration-200">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Selesai
                        </button>
                    </div>
                </div>
            </div>
            @endforeach

        </div>{{-- end quiz questions --}}

    </div>{{-- end max-w container --}}

    <form id="quizForm" method="POST" action="{{ route('siswa.kuis.submit', $kuis) }}" class="hidden">
        @csrf
    </form>
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
            const total = {{ $kuis->questions->count() }};
            const answered = Object.keys(this.answers).length;
            if (answered < total) {
                if (!confirm(`Masih ada ${total - answered} pertanyaan yang belum dijawab. Lanjutkan submit?`)) return;
            }
            const form = document.getElementById('quizForm');
            form.querySelectorAll('.answer-input').forEach(e => e.remove());
            for (const [questionId, optionId] of Object.entries(this.answers)) {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = `answers[${questionId}]`;
                input.value = optionId;
                input.className = 'answer-input';
                form.appendChild(input);
            }
            form.submit();
        }
    }
}
</script>
@endsection
