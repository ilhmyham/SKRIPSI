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
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('kategori_materi')->nullable();
            $table->string('judul_materi');
            $table->text('deskripsi')->nullable();
            $table->string('file_video')->nullable();
            $table->string('huruf_hijaiyah')->nullable();
            $table->string('path_file')->nullable();
            $table->smallInteger('urutan')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
