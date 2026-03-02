<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kuis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_iqra_id')->constrained('modul_iqra')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Guru pembuat
            $table->string('judul_kuis');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->index('modul_iqra_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kuis');
    }
};
