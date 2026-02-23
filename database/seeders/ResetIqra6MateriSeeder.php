<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class ResetIqra6MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra6 = ModulIqra::where('nama_modul', 'Iqra 6')->first();
        
        if (!$iqra6) {
            $this->command->error('Modul Iqra 6 tidak ditemukan!');
            return;
        }

        $oldCount = Materi::where('modul_iqra_modul_id', $iqra6->modul_id)->count();
        $this->command->info("Menghapus {$oldCount} materi lama Iqra 6...");
        
        Materi::where('modul_iqra_modul_id', $iqra6->modul_id)->delete();
        
        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama!");
    }
}
