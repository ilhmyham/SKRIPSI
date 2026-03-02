<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tugas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Guru pembuat
            $table->foreignId('modul_iqra_id')->nullable()->constrained('modul_iqra')->nullOnDelete();
            $table->string('judul_tugas');
            $table->text('deskripsi_tugas')->nullable();
            $table->date('tenggat_waktu');
            $table->timestamps();

            $table->index('tenggat_waktu');
            $table->index(['modul_iqra_id', 'tenggat_waktu']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tugas');
    }
};
