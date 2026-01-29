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
        // Make text fields nullable since images can be used instead
        Schema::table('pertanyaan', function (Blueprint $table) {
            $table->text('text_pertanyaan')->nullable()->change();
        });

        Schema::table('opsi_jawaban', function (Blueprint $table) {
            $table->text('teks_opsi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pertanyaan', function (Blueprint $table) {
            $table->text('text_pertanyaan')->nullable(false)->change();
        });

        Schema::table('opsi_jawaban', function (Blueprint $table) {
            $table->text('teks_opsi')->nullable(false)->change();
        });
    }
};
