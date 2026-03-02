<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_iqra_id')->constrained('modul_iqra')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Guru pembuat
            $table->foreignId('kategori_materi_id')->nullable()->constrained('kategori_materi')->nullOnDelete();
            $table->string('judul_materi');
            $table->text('deskripsi')->nullable();
            $table->string('file_video')->nullable();
            $table->string('huruf_hijaiyah')->nullable();
            $table->string('path_file')->nullable();
            $table->smallInteger('urutan')->unsigned()->nullable();
            $table->timestamps();

            $table->index(['modul_iqra_id', 'kategori_materi_id', 'urutan']);
            $table->index('kategori_materi_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
