<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis_jawaban_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('kuis_pertanyaan_id')->constrained('kuis_pertanyaan')->cascadeOnDelete();
            $table->foreignId('kuis_opsi_jawaban_id')->nullable()->constrained('kuis_opsi_jawaban')->nullOnDelete();
            $table->timestamps();

            $table->unique(['kuis_id', 'kuis_pertanyaan_id', 'user_id'], 'jawaban_unik_siswa');
            $table->index(['kuis_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis_jawaban_siswa');
    }
};
