<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class Iqra2MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra2 = ModulIqra::where('nama_modul', 'Iqra 2')->first();

        if (!$iqra2) {
            $this->command->error('Modul Iqra 2 tidak ditemukan!');
            return;
        }

        // DAFTAR HURUF DENGAN URUTAN STANDAR MODUL (Alif - Ya)
        $listHuruf = [
            ['file' => 'alif',          'name' => 'Alif',   'arab' => 'ا'],
            ['file' => 'ba',            'name' => 'Ba',     'arab' => 'ب'],
            ['file' => 'ta',            'name' => 'Ta',     'arab' => 'ت'],
            ['file' => 'tsa',           'name' => 'Tsa',    'arab' => 'ث'],
            ['file' => 'jim',           'name' => 'Jim',    'arab' => 'ج'],
            ['file' => 'ha',            'name' => 'Ha',     'arab' => 'ح'],
            ['file' => 'kha',           'name' => 'Kha',    'arab' => 'خ'],
            ['file' => 'dal',           'name' => 'Dal',    'arab' => 'د'],
            ['file' => 'dzal',          'name' => 'Dzal',   'arab' => 'ذ'],
            ['file' => 'ra',            'name' => 'Ra',     'arab' => 'ر'],
            ['file' => 'zai',           'name' => 'Zai',    'arab' => 'ز'],
            ['file' => 'sin',           'name' => 'Sin',    'arab' => 'س'],
            ['file' => 'syin',          'name' => 'Syin',   'arab' => 'ش'],
            ['file' => 'shad',          'name' => 'Shad',   'arab' => 'ص'],
            ['file' => 'dhad',          'name' => 'Dhad',   'arab' => 'ض'],
            ['file' => 'tha',           'name' => 'Tha',    'arab' => 'ط'],
            ['file' => 'zha',           'name' => 'Zha',    'arab' => 'ظ'],
            ['file' => 'ain',           'name' => 'Ain',    'arab' => 'ع'],
            ['file' => 'ghain',         'name' => 'Ghain',  'arab' => 'غ'],
            ['file' => 'fa',            'name' => 'Fa',     'arab' => 'ف'],
            ['file' => 'qaf',           'name' => 'Qaf',    'arab' => 'ق'],
            ['file' => 'kaf',           'name' => 'Kaf',    'arab' => 'ك'],
            ['file' => 'lam',           'name' => 'Lam',    'arab' => 'ل'],
            ['file' => 'mim',           'name' => 'Mim',    'arab' => 'م'],
            ['file' => 'nun',           'name' => 'Nun',    'arab' => 'ن'],
            ['file' => 'waw',           'name' => 'Waw',    'arab' => 'و'],
            ['file' => 'ha_besar',      'name' => 'Ha',     'arab' => 'ه'],
            ['file' => 'hamzah',        'name' => 'Hamzah', 'arab' => 'ء'],
            ['file' => 'ya',            'name' => 'Ya',     'arab' => 'ي'],
            ['file' => 'ta_marbutah',   'name' => 'Ta Marbutah', 'arab' => 'ة'],
        ];

        $harakatConfig = [
            ['kategori' => 'fathah', 'nama' => 'Fathah', 'simbol' => 'َ', 'folder' => 'fathah', 'desc' => 'garis lurus dari kanan ke kiri.'],
            ['kategori' => 'kasrah', 'nama' => 'Kasrah', 'simbol' => 'ِ', 'folder' => 'kasrah', 'desc' => 'garis lurus dari atas ke bawah.'],
            ['kategori' => 'dammah', 'nama' => 'Dammah', 'simbol' => 'ُ', 'folder' => 'dhomah', 'desc' => 'garis melengkung ke bawah.'],
        ];

        $this->command->info('Mulai Seeding Iqra 2 (Urutan per Kategori: Alif-Ya)...');

        foreach ($harakatConfig as $harakat) {
            $urutan = 1;
            foreach ($listHuruf as $h) {
                Materi::create([
                    'modul_iqra_id' => $iqra2->id,
                    'user_id' => 1,
                    'judul_materi' => $h['name'] . ' ' . $harakat['nama'],
                    'huruf_hijaiyah' => $h['arab'] . $harakat['simbol'],
                    'kategori_materi' => $harakat['kategori'],
                    'deskripsi' => "Isyarat huruf {$h['name']} diikuti gerakan {$harakat['desc']}",
                    'path_file' => "materi/iqra2/{$harakat['folder']}/{$h['file']}.png",
                    'urutan' => $urutan,
                ]);

                $urutan++;
            }
            $this->command->info("✓ Selesai kategori: " . $harakat['nama']);
        }

        $this->command->info("✅ Sukses! Urutan materi Iqra 2 sekarang sudah sesuai modul.");
    }
}
