<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis_pertanyaan', function (Blueprint $table) {
            $table->id();
            $table->foreignId('kuis_id')->constrained('kuis')->cascadeOnDelete();
            $table->text('teks_pertanyaan')->nullable();
            $table->string('gambar_pertanyaan')->nullable();
            $table->enum('tipe', ['pilihan_ganda'])->default('pilihan_ganda');
            $table->timestamps();

            $table->index('kuis_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis_pertanyaan');
    }
};
