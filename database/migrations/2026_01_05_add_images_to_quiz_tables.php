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
        // Add image support to pertanyaan table
        Schema::table('pertanyaan', function (Blueprint $table) {
            $table->string('gambar_pertanyaan')->nullable()->after('text_pertanyaan');
        });

        // Add image support and rename column in opsi_jawaban table
        Schema::table('opsi_jawaban', function (Blueprint $table) {
            $table->string('gambar_opsi')->nullable()->after('teks_opsi');
            
            // Rename is_correct to is_benar for consistency
            if (Schema::hasColumn('opsi_jawaban', 'is_correct')) {
                $table->renameColumn('is_correct', 'is_benar');
            }
        });

        // Restructure kuis table to be directly under modul
        Schema::table('kuis', function (Blueprint $table) {
            // Drop old foreign key and column
            $table->dropForeign(['materi_id']);
            $table->dropColumn('materi_id');
            
            // Add new columns
            $table->foreignId('modul_iqra_modul_id')
                  ->after('kuis_id')
                  ->constrained('modul_iqra', 'modul_id')
                  ->onDelete('cascade');
                  
            $table->foreignId('users_user_id')
                  ->after('modul_iqra_modul_id')
                  ->constrained('users', 'id')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Reverse kuis table changes
        Schema::table('kuis', function (Blueprint $table) {
            $table->dropForeign(['modul_iqra_modul_id']);
            $table->dropForeign(['users_user_id']);
            $table->dropColumn(['modul_iqra_modul_id', 'users_user_id']);
            
            $table->foreignId('materi_id')
                  ->after('kuis_id')
                  ->constrained('materi', 'materi_id')
                  ->onDelete('cascade');
        });

        // Reverse opsi_jawaban changes
        Schema::table('opsi_jawaban', function (Blueprint $table) {
            $table->dropColumn('gambar_opsi');
            
            if (Schema::hasColumn('opsi_jawaban', 'is_benar')) {
                $table->renameColumn('is_benar', 'is_correct');
            }
        });

        // Reverse pertanyaan changes
        Schema::table('pertanyaan', function (Blueprint $table) {
            $table->dropColumn('gambar_pertanyaan');
        });
    }
};
