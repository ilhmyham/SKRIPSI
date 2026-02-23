<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class ResetIqra5MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra5 = ModulIqra::where('nama_modul', 'Iqra 5')->first();
        
        if (!$iqra5) {
            $this->command->error('Modul Iqra 5 tidak ditemukan!');
            return;
        }

        $oldCount = Materi::where('modul_iqra_modul_id', $iqra5->modul_id)->count();
        $this->command->info("Menghapus {$oldCount} materi lama Iqra 5...");
        
        Materi::where('modul_iqra_modul_id', $iqra5->modul_id)->delete();
        
        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama!");
    }
}
