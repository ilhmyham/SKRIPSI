<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kategori_materi', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modul_iqra_id')->constrained('modul_iqra')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->smallInteger('urutan')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['modul_iqra_id', 'nama']);
            $table->index('modul_iqra_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kategori_materi');
    }
};
