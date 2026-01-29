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
        Schema::create('jawaban_siswa', function (Blueprint $table) {
            $table->id('jawaban_id');
            $table->foreignId('kuis_id')->constrained('kuis', 'kuis_id')->onDelete('cascade');
            $table->foreignId('users_user_id')->constrained('users', 'id')->onDelete('cascade'); // Student
            $table->foreignId('pertanyaan_id')->constrained('pertanyaan', 'pertanyaan_id')->onDelete('cascade');
            $table->string('jawaban_pilihan')->nullable();
            $table->float('nilai')->nullable();
            $table->timestamp('waktu_dikerjakan')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jawaban_siswa');
    }
};
