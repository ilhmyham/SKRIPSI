<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('quizzes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Guru pembuat
            $table->string('judul_kuis');
            $table->text('deskripsi')->nullable();
            $table->timestamps();

            $table->index('module_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('quizzes');
    }
};
