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
        Schema::create('progress_belajar', function (Blueprint $table) {
            $table->id('progress_id');
            $table->foreignId('materi_id')->constrained('materi', 'materi_id')->onDelete('cascade');
            $table->foreignId('users_user_id')->constrained('users', 'id')->onDelete('cascade'); // Student
            $table->enum('status_2', ['selesai', 'belum'])->default('belum');
            $table->float('progress_value')->default(0); // 0-100
            $table->timestamp('tanggal_update')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('progress_belajar');
    }
};
