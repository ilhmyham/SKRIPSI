<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class DebugIqra2Seeder extends Seeder
{
    public function run(): void
    {
        $iqra2 = ModulIqra::where('nama_modul', 'Iqra 2')->first();
        
        if (!$iqra2) {
            $this->command->error('Iqra 2 tidak ditemukan!');
            return;
        }

        $total = Materi::where('modul_iqra_modul_id', $iqra2->modul_id)->count();
        $this->command->info("Total Iqra 2: {$total} materi");

        // Group by kategori
        $grouped = Materi::where('modul_iqra_modul_id', $iqra2->modul_id)
            ->selectRaw('kategori, COUNT(*) as total')
            ->groupBy('kategori')
            ->get();

        $this->command->info("\nBreakdown per kategori:");
        foreach ($grouped as $g) {
            $this->command->info("  - {$g->kategori}: {$g->total} materi");
        }

        // Show sample paths
        $this->command->info("\nContoh 3 path gambar:");
        Materi::where('modul_iqra_modul_id', $iqra2->modul_id)
            ->take(3)
            ->get()
            ->each(function($m) {
                $this->command->info("  - {$m->judul_materi}: {$m->file_path}");
            });
    }
}
