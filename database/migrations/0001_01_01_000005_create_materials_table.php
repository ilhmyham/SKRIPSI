<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // Guru pembuat
            $table->foreignId('category_id')->nullable()->constrained('material_categories')->nullOnDelete();
            $table->string('judul_materi');
            $table->text('deskripsi')->nullable();
            $table->string('file_video')->nullable();
            $table->string('huruf_hijaiyah')->nullable();
            $table->string('file_path')->nullable();
            $table->smallInteger('urutan')->unsigned()->nullable();
            $table->timestamps();

            $table->index(['module_id', 'category_id', 'urutan']);
            $table->index('category_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('materials');
    }
};
