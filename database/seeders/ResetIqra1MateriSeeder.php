<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;
use Illuminate\Support\Facades\DB;

class ResetIqra1MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get Iqra 1 module ID
        $iqra1 = ModulIqra::where('nama_modul', 'Iqra 1')->first();
        
        if (!$iqra1) {
            $this->command->error('Modul Iqra 1 tidak ditemukan!');
            return;
        }

        // Count existing materials
        $oldCount = Materi::where('modul_iqra_modul_id', $iqra1->modul_id)->count();
        
        $this->command->info("Menghapus {$oldCount} materi lama Iqra 1...");
        
        // Delete all old Iqra 1 materials
        Materi::where('modul_iqra_modul_id', $iqra1->modul_id)->delete();
        
        $this->command->info("✅ Berhasil menghapus {$oldCount} materi lama!");
        $this->command->info("Sekarang jalankan: php artisan db:seed --class=Iqra1MateriSeeder");
    }
}
