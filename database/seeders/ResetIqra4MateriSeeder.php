<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class ResetIqra4MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra4 = ModulIqra::where('nama_modul', 'Iqra 4')->first();
        
        if (!$iqra4) {
            $this->command->error('Modul Iqra 4 tidak ditemukan!');
            return;
        }

        $oldCount = Materi::where('modul_iqra_modul_id', $iqra4->modul_id)->count();
        $this->command->info("Menghapus {$oldCount} materi lama Iqra 4...");
        
        Materi::where('modul_iqra_modul_id', $iqra4->modul_id)->delete();
        
        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama!");
    }
}
