<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis_opsi_jawaban', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_pertanyaan_id')->constrained('kuis_pertanyaan')->cascadeOnDelete();
            $table->text('teks_opsi')->nullable();
            $table->string('gambar_opsi')->nullable();
            $table->boolean('is_correct')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis_opsi_jawaban');
    }
};
