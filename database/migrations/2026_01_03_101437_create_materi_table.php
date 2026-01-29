<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('materi', function (Blueprint $table) {
            $table->id('materi_id');
            $table->foreignId('modul_iqra_modul_id')->constrained('modul_iqra', 'modul_id')->onDelete('cascade');
            $table->foreignId('users_user_id')->constrained('users', 'id')->onDelete('cascade'); // Teacher who created
            $table->string('judul_materi');
            $table->text('deskripsi')->nullable();
            $table->string('file_video')->nullable(); // Google Drive Video ID
            $table->string('huruf_hijaiyah')->nullable();
            $table->string('file_path')->nullable(); // Thumbnail image path
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('materi');
    }
};
