<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Iqra5MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder Iqra 5: Mengenal Bacaan Panjang (Mad).
     * Update: Reset urutan per kategori (mad2, mad4_5, mad6).
     */
    public function run(): void
    {
        $iqra5 = Module::where('nama_modul', 'Iqra 5')->first();
        
        if (!$iqra5) {
            $this->command->error('Modul Iqra 5 tidak ditemukan!');
            return;
        }

        $getCategoryId = function($kategori) use ($iqra5) {
            $slug = Str::slug($kategori, '_');
            return DB::table('material_categories')
                ->where('module_id', $iqra5->id)
                ->where('nama', $slug)
                ->value('id');
        };

        // ==========================================================
        // DATA MATERI
        // ==========================================================

        $mad2Harakat = [
            ['judul' => 'Mad 2 Harakat: Adzabun', 'huruf' => 'عَذَابٌ', 'file' => 'adzabun.png', 'kategori' => 'mad_2_harakat', 'deskripsi' => 'Mad Thabi\'i (Alif setelah Fathah). Isyarat huruf Dzal ditahan 2 ketukan.'],
            ['judul' => 'Mad 2 Harakat: Yuqima', 'huruf' => 'يُقِيْمَا', 'file' => 'yuqima.png', 'kategori' => 'mad_2_harakat', 'deskripsi' => 'Mad Thabi\'i (Ya Sukun setelah Kasrah). Isyarat huruf Qaf ditahan 2 ketukan.'],
            ['judul' => 'Mad 2 Harakat: Kafaru', 'huruf' => 'كَفَرُوْا', 'file' => 'kafaru.png', 'kategori' => 'mad_2_harakat', 'deskripsi' => 'Mad Thabi\'i (Wau Sukun setelah Dammah). Isyarat huruf Ra ditahan 2 ketukan.'],
            ['judul' => 'Mad 2 Harakat: Dzalika', 'huruf' => 'ذٰلِكَ', 'file' => 'dzalika.png', 'kategori' => 'mad_2_harakat', 'deskripsi' => 'Fathah Tegak (Mad Asli). Isyarat huruf Dzal ditahan 2 ketukan.'],
            ['judul' => 'Mad 2 Harakat: Nafsihi', 'huruf' => 'نَفْسِهٖ', 'file' => 'nafsihi.png', 'kategori' => 'mad_2_harakat', 'deskripsi' => 'Kasrah Tegak (Mad Shilah Qashirah). Isyarat huruf Ha ditahan 2 ketukan.'],
        ];

        $mad45Harakat = [
            ['judul' => 'Mad 4-5 Harakat: Haba\'an', 'huruf' => 'هَبَاۤءً', 'file' => 'habaan.png', 'kategori' => 'mad_4_5_harakat', 'deskripsi' => 'Mad Wajib Muttasil. Huruf Ba bertemu Hamzah dalam satu kata. Ditahan 4-5 ketukan.'],
            ['judul' => 'Mad 4-5 Harakat: Jaza\'an', 'huruf' => 'جَزَاۤءً', 'file' => 'jazaan.png', 'kategori' => 'mad_4_5_harakat', 'deskripsi' => 'Mad Wajib Muttasil. Huruf Zai bertemu Hamzah dalam satu kata. Ditahan 4-5 ketukan.'],
            ['judul' => 'Mad 4-5 Harakat: Ula\'ika', 'huruf' => 'أُوْلٰۤئِكَ', 'file' => 'ulaika.png', 'kategori' => 'mad_4_5_harakat', 'deskripsi' => 'Mad Wajib Muttasil. Huruf Lam (Tanda Layar) bertemu Hamzah. Ditahan 4-5 ketukan.'],
        ];

        $mad6Harakat = [
            ['judul' => 'Mad 6 Harakat: Dhallin', 'huruf' => 'ضَآلًّا', 'file' => 'dhallin.png', 'kategori' => 'mad_6_harakat', 'deskripsi' => 'Mad Lazim Kilmi Mutsaqqal. Huruf Dhad bertemu Lam Tasydid. Ditahan 6 ketukan.'],
            ['judul' => 'Mad 6 Harakat: Yuwadduna', 'huruf' => 'يُوَادُّوْنَ', 'file' => 'yuwadduna.png', 'kategori' => 'mad_6_harakat', 'deskripsi' => 'Huruf Wau bertemu Dal Tasydid. Isyarat Wau ditahan 6 ketukan.'],
            ['judul' => 'Mad 6 Harakat: Adz-Dzakaraini', 'huruf' => 'الذَّكَرَيْنِ', 'file' => 'adz_dzakaraini.png', 'kategori' => 'mad_6_harakat', 'deskripsi' => 'Mad Lazim. Pertemuan Hamzah Istifham dengan Lam Ta\'rif. Ditahan 6 ketukan.'],
        ];

        $this->command->info('Mulai Seeding Iqra 5 (Urutan per Kategori)...');

        // Fungsi Helper untuk Insert Data dengan Reset Urutan per Folder
        $insertData = function($dataList, $folderName) use ($iqra5, $getCategoryId) {
            $urutan = 1; // Reset urutan menjadi 1 untuk setiap kategori Mad baru
            foreach ($dataList as $item) {
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
            $this->command->info("✓ Selesai kategori: " . $folderName);
        };

        // Eksekusi per kategori
        $insertData($mad2Harakat, 'mad2');
        $insertData($mad45Harakat, 'mad4_5');
        $insertData($mad6Harakat, 'mad6');

        $this->command->info("✅ SELESAI. Semua materi Iqra 5 telah dibuat dengan urutan per kategori.");
    }
}