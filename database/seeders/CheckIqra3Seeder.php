<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class CheckIqra3Seeder extends Seeder
{
    public function run(): void
    {
        $iqra3 = ModulIqra::where('nama_modul', 'Iqra 3')->first();
        
        if (!$iqra3) {
            $this->command->error('❌ Modul Iqra 3 TIDAK DITEMUKAN di database!');
            $this->command->info('Modul yang ada:');
            ModulIqra::all()->each(function($m) {
                $this->command->info("  - {$m->nama_modul} (ID: {$m->modul_id})");
            });
            return;
        }
        
        $this->command->info("✅ Modul Iqra 3 ditemukan (ID: {$iqra3->modul_id})");
        
        $count = Materi::where('modul_iqra_modul_id', $iqra3->modul_id)->count();
        $this->command->info("✅ Total materi Iqra 3: {$count}");
        
        if ($count > 0) {
            $this->command->info("\nContoh 5 materi pertama:");
            Materi::where('modul_iqra_modul_id', $iqra3->modul_id)
                ->take(5)
                ->get()
                ->each(function($m) {
                    $this->command->info("  - {$m->judul_materi} ({$m->huruf_hijaiyah})");
                });
        }
    }
}
