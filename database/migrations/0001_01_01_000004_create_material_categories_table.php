<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('material_categories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('module_id')->constrained('modules')->cascadeOnDelete();
            $table->string('nama', 100);
            $table->smallInteger('urutan')->unsigned()->nullable();
            $table->timestamps();

            $table->unique(['module_id', 'nama']);
            $table->index('module_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('material_categories');
    }
};
