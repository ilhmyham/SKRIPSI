<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class CheckIqra1Seeder extends Seeder
{
    public function run(): void
    {
        $iqra1 = ModulIqra::where('nama_modul', 'Iqra 1')->first();
        
        if (!$iqra1) {
            $this->command->error('Iqra 1 tidak ditemukan!');
            return;
        }

        $total = Materi::where('modul_iqra_modul_id', $iqra1->modul_id)->count();
        $this->command->info("Total Iqra 1: {$total} materi");

        // Show first 5 materials
        $this->command->info("\n5 Materi Pertama:");
        Materi::where('modul_iqra_modul_id', $iqra1->modul_id)
            ->orderBy('materi_id', 'asc')
            ->take(5)
            ->get()
            ->each(function($m) {
                $this->command->info("  [{$m->materi_id}] {$m->judul_materi} - {$m->huruf_hijaiyah} - Path: {$m->file_path}");
            });
    }
}
