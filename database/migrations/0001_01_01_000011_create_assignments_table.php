<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Guru pembuat
            $table->foreignId('module_id')->nullable()->constrained('modules')->nullOnDelete();
            $table->string('judul_tugas');
            $table->text('deskripsi_tugas')->nullable();
            $table->date('deadline');
            $table->timestamps();

            $table->index('deadline');
            $table->index(['module_id', 'deadline']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('assignments');
    }
};
