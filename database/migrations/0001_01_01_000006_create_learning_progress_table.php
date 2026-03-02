<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('progress_belajar', function (Blueprint $table) {
            $table->id();
            $table->foreignId('materi_id')->constrained('materi')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->enum('status', ['belum', 'selesai'])->default('belum');
            $table->float('nilai_progress')->default(0);
            $table->timestamps();

            $table->unique(['materi_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('progress_belajar');
    }
};
