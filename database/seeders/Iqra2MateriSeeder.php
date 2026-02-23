<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;
use Illuminate\Support\Str;

class Iqra2MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra2 = Module::where('nama_modul', 'Iqra 2')->first();
        
        $getCategoryId = function($kategori) use ($iqra2) {
            $slug = Str::slug($kategori, '_');
            return \Illuminate\Support\Facades\DB::table('material_categories')
                ->where('module_id', $iqra2->id)
                ->where('nama', $slug)
                ->value('id');
        };
        
        if (!$iqra2) {
            $this->command->error('Modul Iqra 2 tidak ditemukan!');
            return;
        }

        // DAFTAR INDUK HURUF
        // Kolom 'file' harus SAMA PERSIS dengan nama file yang sudah direname (tanpa .png)
        $listHuruf = [
            ['file' => 'alif',          'name' => 'Alif',   'arab' => 'ا'],
            ['file' => 'ain',           'name' => 'Ain',    'arab' => 'ع'], 
            ['file' => 'ba',            'name' => 'Ba',     'arab' => 'ب'],
            ['file' => 'ta',            'name' => 'Ta',     'arab' => 'ت'],
            ['file' => 'tha',           'name' => 'Tha',    'arab' => 'ط'], 
            ['file' => 'tsa',           'name' => 'Tsa',    'arab' => 'ث'],
            ['file' => 'sin',           'name' => 'Sin',    'arab' => 'س'],
            ['file' => 'syin',          'name' => 'Syin',   'arab' => 'ش'],
            ['file' => 'shad',          'name' => 'Shad',   'arab' => 'ص'], 
            ['file' => 'jim',           'name' => 'Jim',    'arab' => 'ج'],
            ['file' => 'ha',            'name' => 'Ha',     'arab' => 'ح'],
            ['file' => 'kha',           'name' => 'Kha',    'arab' => 'خ'],
            ['file' => 'ha_besar',      'name' => 'Ha',     'arab' => 'ه'], 
            ['file' => 'dal',           'name' => 'Dal',    'arab' => 'د'],
            ['file' => 'dad',           'name' => 'Dad',    'arab' => 'ض'],
            ['file' => 'dzal',          'name' => 'Dzal',   'arab' => 'ذ'], 
            ['file' => 'zai',           'name' => 'Zai',    'arab' => 'ز'],
            ['file' => 'zha',           'name' => 'Zha',    'arab' => 'ظ'], 
            ['file' => 'ra',            'name' => 'Ra',     'arab' => 'ر'],
            ['file' => 'ghain',         'name' => 'Ghain',  'arab' => 'غ'],
            ['file' => 'fa',            'name' => 'Fa',     'arab' => 'ف'],
            ['file' => 'qaf',           'name' => 'Qaf',    'arab' => 'ق'],
            ['file' => 'kaf',           'name' => 'Kaf',    'arab' => 'ك'],
            ['file' => 'lam',           'name' => 'Lam',    'arab' => 'ل'],
            ['file' => 'mim',           'name' => 'Mim',    'arab' => 'م'],
            ['file' => 'nun',           'name' => 'Nun',    'arab' => 'ن'],
            ['file' => 'waw',           'name' => 'Waw',    'arab' => 'و'],
            ['file' => 'ya',            'name' => 'Ya',     'arab' => 'ي'],
            ['file' => 'ta_marbutah',   'name' => 'Ta Marbutah', 'arab' => 'ة'],              
            ['file' => 'hamzah',        'name' => 'Hamzah', 'arab' => 'ء'],            
        ];

        // KONFIGURASI HARAKAT (Folder: materi/iqra2/fathah/ dll)
        $harakatConfig = [
            ['kategori' => 'fathah', 'nama' => 'Fathah', 'simbol' => 'َ', 'folder' => 'fathah', 'desc' => 'garis lurus dari kanan ke kiri.'],
            ['kategori' => 'kasrah', 'nama' => 'Kasrah', 'simbol' => 'ِ', 'folder' => 'kasrah', 'desc' => 'garis lurus dari atas ke bawah.'],
            ['kategori' => 'dammah', 'nama' => 'Dammah', 'simbol' => 'ُ', 'folder' => 'dhomah', 'desc' => 'garis melengkung ke bawah.'],
        ];

        $this->command->info('Mulai Seeding Iqra 2 (Nama File Standard)...');
        $urutan = 1;

        foreach ($listHuruf as $h) {
            foreach ($harakatConfig as $harakat) {
                
                // Pastikan file gambar ada sebelum insert (Opsional, untuk debug)
                // $imagePath = "assets/images/iqra2/{$harakat['folder']}/{$h['file']}.png";
                
                Material::create([
                    'module_id' => $iqra2->id,
                    'user_id' => 1,
                    'judul_materi' => $h['name'] . ' ' . $harakat['nama'],
                    'huruf_hijaiyah' => $h['arab'] . $harakat['simbol'],
                    'category_id' => $getCategoryId($harakat['kategori']),
                    'deskripsi' => "Isyarat huruf {$h['name']} diikuti gerakan {$harakat['desc']}",
                    
                    // Path Gambar Otomatis
                    'file_path' => "materi/iqra2/{$harakat['folder']}/{$h['file']}.png",
                    'urutan' => $urutan,
                ]);
                $urutan++;
            }
        }

        $this->command->info("✅ Sukses! Materi Iqra 2 Berhasil Dibuat.");
    }
}
