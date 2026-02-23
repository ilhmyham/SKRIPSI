<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;
use Illuminate\Support\Str;

class Iqra5MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder Iqra 5: Mengenal Bacaan Panjang (Mad).
     * Fitur: Path gambar disesuaikan dengan nama file latin & folder.
     */
    public function run(): void
    {
        $iqra5 = Module::where('nama_modul', 'Iqra 5')->first();
        
        $getCategoryId = function($kategori) use ($iqra5) {
            $slug = Str::slug($kategori, '_');
            return \Illuminate\Support\Facades\DB::table('material_categories')
                ->where('module_id', $iqra5->id)
                ->where('nama', $slug)
                ->value('id');
        };
        
        if (!$iqra5) {
            $this->command->error('Modul Iqra 5 tidak ditemukan!');
            return;
        }

        // ==========================================================
        // KATEGORI 1: MAD 2 HARAKAT
        // Folder: assets/images/iqra5/mad2/
        // ==========================================================
        $mad2Harakat = [
            [
                'judul' => 'Mad 2 Harakat: Adzabun',
                'huruf' => 'عَذَابٌ', 
                'file'  => 'adzabun.png', // Sesuai nama file Anda
                'deskripsi' => 'Mad Thabi\'i (Alif setelah Fathah). Isyarat huruf Dzal ditahan 2 ketukan.',
                'kategori' => 'mad_2_harakat'
            ],
            [
                'judul' => 'Mad 2 Harakat: Yuqima',
                'huruf' => 'يُقِيْمَا', 
                'file'  => 'yuqima.png',
                'deskripsi' => 'Mad Thabi\'i (Ya Sukun setelah Kasrah). Isyarat huruf Qaf ditahan 2 ketukan.',
                'kategori' => 'mad_2_harakat'
            ],
            [
                'judul' => 'Mad 2 Harakat: Kafaru',
                'huruf' => 'كَفَرُوْا', 
                'file'  => 'kafaru.png',
                'deskripsi' => 'Mad Thabi\'i (Wau Sukun setelah Dammah). Isyarat huruf Ra ditahan 2 ketukan.',
                'kategori' => 'mad_2_harakat'
            ],
            [
                'judul' => 'Mad 2 Harakat: Dzalika',
                'huruf' => 'ذٰلِكَ', 
                'file'  => 'dzalika.png',
                'deskripsi' => 'Fathah Tegak (Mad Asli). Isyarat huruf Dzal ditahan 2 ketukan.',
                'kategori' => 'mad_2_harakat'
            ],
            [
                'judul' => 'Mad 2 Harakat: Nafsihi',
                'huruf' => 'نَفْسِهٖ', 
                'file'  => 'nafsihi.png',
                'deskripsi' => 'Kasrah Tegak (Mad Shilah Qashirah). Isyarat huruf Ha ditahan 2 ketukan.',
                'kategori' => 'mad_2_harakat'
            ],
        ];

        // ==========================================================
        // KATEGORI 2: MAD 4-5 HARAKAT
        // Folder: assets/images/iqra5/mad4_5/
        // ==========================================================
        $mad45Harakat = [
            [
                'judul' => 'Mad 4-5 Harakat: Haba\'an',
                'huruf' => 'هَبَاۤءً', 
                'file'  => 'habaan.png',
                'deskripsi' => 'Mad Wajib Muttasil. Huruf Ba bertemu Hamzah dalam satu kata. Ditahan 4-5 ketukan.',
                'kategori' => 'mad_4_5_harakat'
            ],
            [
                'judul' => 'Mad 4-5 Harakat: Jaza\'an',
                'huruf' => 'جَزَاۤءً',
                'file'  => 'jazaan.png',
                'deskripsi' => 'Mad Wajib Muttasil. Huruf Zai bertemu Hamzah dalam satu kata. Ditahan 4-5 ketukan.',
                'kategori' => 'mad_4_5_harakat'
            ],
            [
                'judul' => 'Mad 4-5 Harakat: Ula\'ika',
                'huruf' => 'أُوْلٰۤئِكَ', 
                'file'  => 'ulaika.png',
                'deskripsi' => 'Mad Wajib Muttasil. Huruf Lam (Tanda Layar) bertemu Hamzah. Ditahan 4-5 ketukan.',
                'kategori' => 'mad_4_5_harakat'
            ],
        ];

        // ==========================================================
        // KATEGORI 3: MAD 6 HARAKAT
        // Folder: assets/images/iqra5/mad6/
        // ==========================================================
        $mad6Harakat = [
            [
                'judul' => 'Mad 6 Harakat: Dhallin',
                'huruf' => 'ضَآلًّا', 
                'file'  => 'dhallin.png',
                'deskripsi' => 'Mad Lazim Kilmi Mutsaqqal. Huruf Dhad bertemu Lam Tasydid. Ditahan 6 ketukan.',
                'kategori' => 'mad_6_harakat'
            ],
            [
                'judul' => 'Mad 6 Harakat: Yuwadduna',
                'huruf' => 'يُوَادُّوْنَ', 
                'file'  => 'yuwadduna.png',
                'deskripsi' => 'Huruf Wau bertemu Dal Tasydid. Isyarat Wau ditahan 6 ketukan.',
                'kategori' => 'mad_6_harakat'
            ],
            [
                'judul' => 'Mad 6 Harakat: Adz-Dzakaraini',
                'huruf' => 'الذَّكَرَيْنِ', 
                'file'  => 'adz_dzakaraini.png', // Sesuaikan nama file: adz_dzakaraini.png
                'deskripsi' => 'Mad Lazim. Pertemuan Hamzah Istifham dengan Lam Ta\'rif. Ditahan 6 ketukan.',
                'kategori' => 'mad_6_harakat'
            ],
        ];

        $this->command->info('Mulai Seeding Iqra 5 (Materi Mad + Gambar)...');
        $urutan = 1;

        // Fungsi Helper untuk Insert Data dengan Folder Spesifik
        $insertData = function($dataList, $folderName) use ($iqra5, $getCategoryId, &$urutan) {
            foreach ($dataList as $item) {
                
                // Membuat Path Gambar: assets/images/iqra5/mad2/adzabun.png
                $filePath = 'materi/iqra5/' . $folderName . '/' . $item['file'];

                Material::create([
                    'module_id' => $iqra5->id,
                    'user_id' => 1,
                    'judul_materi' => $item['judul'],
                    'huruf_hijaiyah' => $item['huruf'],
                    'category_id' => $getCategoryId($item['kategori']),
                    'deskripsi' => $item['deskripsi'],
                    'file_path' => $filePath, 
                    'urutan' => $urutan++,
                ]);
            }
        };

        // Eksekusi per kategori dengan nama folder yang sesuai gambar Anda
        $insertData($mad2Harakat, 'mad2');
        $this->command->info("✓ Berhasil input Mad 2 Harakat (Folder: mad2).");

        $insertData($mad45Harakat, 'mad4_5'); // Perhatikan nama folder: mad4_5
        $this->command->info("✓ Berhasil input Mad 4-5 Harakat (Folder: mad4_5).");

        $insertData($mad6Harakat, 'mad6');
        $this->command->info("✓ Berhasil input Mad 6 Harakat (Folder: mad6).");

        $this->command->info("✅ SELESAI. Total Materi Iqra 5 berhasil dibuat.");
    }
}
