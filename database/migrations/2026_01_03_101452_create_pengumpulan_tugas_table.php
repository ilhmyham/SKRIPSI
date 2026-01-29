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
        Schema::create('pengumpulan_tugas', function (Blueprint $table) {
            $table->id('pengumpulan_id');
            $table->foreignId('users_user_id')->constrained('users', 'id')->onDelete('cascade'); // Student
            $table->foreignId('tugas_id')->constrained('tugas', 'tugas_id')->onDelete('cascade');
            $table->string('file_jawaban')->nullable();
            $table->float('nilai')->nullable();
            $table->timestamp('tanggal_kumpul')->useCurrent();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pengumpulan_tugas');
    }
};
