<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;
use Illuminate\Support\Str;

class Iqra3MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder Iqra 3: Tanwin, Sukun, dan Tasydid.
     * Update: Hamzah di awal, Alif dihapus, Mapping Gambar diperbaiki.
     */
    public function run(): void
    {
        $iqra3 = Module::where('nama_modul', 'Iqra 3')->first();
        
        $getCategoryId = function($kategori) use ($iqra3) {
            $slug = Str::slug($kategori, '_');
            return \Illuminate\Support\Facades\DB::table('material_categories')
                ->where('module_id', $iqra3->id)
                ->where('nama', $slug)
                ->value('id');
        };
        
        if (!$iqra3) {
            $this->command->error('Modul Iqra 3 tidak ditemukan!');
            return;
        }

        // Daftar Huruf Dasar: Hamzah di awal, Alif dihapus
        $listHuruf = [
            ['slug' => 'hamzah',   'name' => 'Hamzah',   'arab' => 'ء', 'bunyi' => ''],
            ['slug' => 'ba',       'name' => 'Ba',       'arab' => 'ب', 'bunyi' => 'b'],
            ['slug' => 'ta',       'name' => 'Ta',       'arab' => 'ت', 'bunyi' => 't'],
            ['slug' => 'tsa',      'name' => 'Tsa',      'arab' => 'ث', 'bunyi' => 'ts'],
            ['slug' => 'jim',      'name' => 'Jim',      'arab' => 'ج', 'bunyi' => 'j'],
            ['slug' => 'ha',       'name' => 'Ha',       'arab' => 'ح', 'bunyi' => 'h'],
            ['slug' => 'kha',      'name' => 'Kha',      'arab' => 'خ', 'bunyi' => 'kh'],
            ['slug' => 'dal',      'name' => 'Dal',      'arab' => 'د', 'bunyi' => 'd'],
            ['slug' => 'dzal',     'name' => 'Dzal',     'arab' => 'ذ', 'bunyi' => 'dz'],
            ['slug' => 'ra',       'name' => 'Ra',       'arab' => 'ر', 'bunyi' => 'r'],
            ['slug' => 'zai',      'name' => 'Zai',      'arab' => 'ز', 'bunyi' => 'z'],
            ['slug' => 'sin',      'name' => 'Sin',      'arab' => 'س', 'bunyi' => 's'],
            ['slug' => 'syin',     'name' => 'Syin',     'arab' => 'ش', 'bunyi' => 'sy'],
            ['slug' => 'shad',     'name' => 'Shad',     'arab' => 'ص', 'bunyi' => 's'],
            ['slug' => 'dhad',     'name' => 'Dhad',     'arab' => 'ض', 'bunyi' => 'dh'],
            ['slug' => 'tha',      'name' => 'Tha',      'arab' => 'ط', 'bunyi' => 'th'],
            ['slug' => 'zha',      'name' => 'Zha',      'arab' => 'ظ', 'bunyi' => 'zh'],
            ['slug' => 'ain',      'name' => 'Ain',      'arab' => 'ع', 'bunyi' => 'ng'],
            ['slug' => 'ghain',    'name' => 'Ghain',    'arab' => 'غ', 'bunyi' => 'g'],
            ['slug' => 'fa',       'name' => 'Fa',       'arab' => 'ف', 'bunyi' => 'f'],
            ['slug' => 'qaf',      'name' => 'Qaf',      'arab' => 'ق', 'bunyi' => 'q'],
            ['slug' => 'kaf',      'name' => 'Kaf',      'arab' => 'ك', 'bunyi' => 'k'],
            ['slug' => 'lam',      'name' => 'Lam',      'arab' => 'ل', 'bunyi' => 'l'],
            ['slug' => 'mim',      'name' => 'Mim',      'arab' => 'م', 'bunyi' => 'm'],
            ['slug' => 'nun',      'name' => 'Nun',      'arab' => 'ن', 'bunyi' => 'n'],
            ['slug' => 'waw',      'name' => 'Waw',      'arab' => 'و', 'bunyi' => 'w'],
            ['slug' => 'ha_besar', 'name' => 'Ha',       'arab' => 'ه', 'bunyi' => 'h'],
            ['slug' => 'ya',       'name' => 'Ya',       'arab' => 'ي', 'bunyi' => 'y'],
            ['slug' => 'ta_marbutah', 'name' => 'Ta Marbutah', 'arab' => 'ة', 'bunyi' => 't'],
        ];

        $this->command->info('Mulai Seeding Iqra 3 (Tanwin, Sukun, Tasydid)...');
        $urutan = 1;

        foreach ($listHuruf as $h) {
            
            // Pengaturan bunyi vokal: Jika Hamzah (tanpa konsonan) maka 'an', 'in', 'un'
            $vokalAn = $h['bunyi'] . 'an';
            $vokalIn = $h['bunyi'] . 'in';
            $vokalUn = $h['bunyi'] . 'un';

            // Mapping nama file gambar sesuai screenshot Anda
            // Contoh: hamzah -> an.png, ba -> ban.png, ha_besar -> han_besar.png
            $fileAn = ($h['slug'] == 'ha_besar') ? 'han_besar.png' : (($h['slug'] == 'ta_marbutah') ? 'tan_marbutah.png' : $vokalAn . '.png');
            $fileIn = ($h['slug'] == 'ha_besar') ? 'hin_besar.png' : (($h['slug'] == 'ta_marbutah') ? 'tin_marbutah.png' : $vokalIn . '.png');
            $fileUn = ($h['slug'] == 'ha_besar') ? 'hun_besar.png' : (($h['slug'] == 'ta_marbutah') ? 'tun_marbutah.png' : $vokalUn . '.png');

            // 1. FATHATAIN (An)
            $hurufAn = $h['arab'] . 'ً'; 
            // Aturan: Tambah Alif kecuali Hamzah & Ta Marbutah
            if ($h['slug'] !== 'ta_marbutah' && $h['slug'] !== 'hamzah') {
                $hurufAn .= 'ا'; 
            }

            Material::create([
                'module_id' => $iqra3->id,
                'user_id' => 1,
                'judul_materi' => "Fathatain: {$h['name']} (an)",
                'huruf_hijaiyah' => $hurufAn,
                'category_id' => $getCategoryId('fathatain'),
                'deskripsi' => "Huruf {$h['name']} berharakat Fathatain, dibaca 'an'.",
                'file_path' => "materi/iqra3/fathah_tanwin/{$fileAn}",
                'urutan' => $urutan++,
            ]);

            // 2. KASRATAIN (In)
            Material::create([
                'module_id' => $iqra3->id,
                'user_id' => 1,
                'judul_materi' => "Kasratain: {$h['name']} (in)",
                'huruf_hijaiyah' => $h['arab'] . 'ٍ',
                'category_id' => $getCategoryId('kasratain'),
                'deskripsi' => "Huruf {$h['name']} berharakat Kasratain, dibaca 'in'.",
                'file_path' => "materi/iqra3/kasrah_tanwin/{$fileIn}",
                'urutan' => $urutan++,
            ]);

            // 3. DAMMATAIN (Un)
            Material::create([
                'module_id' => $iqra3->id,
                'user_id' => 1,
                'judul_materi' => "Dammatain: {$h['name']} (un)",
                'huruf_hijaiyah' => $h['arab'] . 'ٌ',
                'category_id' => $getCategoryId('dammatain'),
                'deskripsi' => "Huruf {$h['name']} berharakat Dammatain, dibaca 'un'.",
                'file_path' => "materi/iqra3/dammah_tanwin/{$fileUn}",
                'urutan' => $urutan++,
            ]);
        }

        // 4. SUKUN (Materi tambahan sesuai modul)
        $contohSukun = [
            ['judul' => 'Sukun: Ab',  'huruf' => 'أَبْ', 'file' => 'ab.png',  'desc' => 'Alif Fathah bertemu Ba Sukun.'],
            ['judul' => 'Sukun: Ah',  'huruf' => 'أَحْ', 'file' => 'ah.png',  'desc' => 'Alif Fathah bertemu Ha Sukun.'],
            ['judul' => 'Sukun: Aj',  'huruf' => 'أَجْ', 'file' => 'aj.png',  'desc' => 'Alif Fathah bertemu Jim Sukun.'],
            ['judul' => 'Sukun: At',  'huruf' => 'أَتْ', 'file' => 'at.png',  'desc' => 'Alif Fathah bertemu Ta Sukun.'],
            ['judul' => 'Sukun: Ats', 'huruf' => 'أَثْ', 'file' => 'ats.png', 'desc' => 'Alif Fathah bertemu Tsa Sukun.'],
        ];

        foreach ($contohSukun as $item) {
            Material::create([
                'module_id' => $iqra3->id,
                'user_id' => 1,
                'judul_materi' => $item['judul'],
                'huruf_hijaiyah' => $item['huruf'],
                'category_id' => $getCategoryId('sukun'),
                'deskripsi' => $item['desc'],
                'file_path' => "materi/iqra3/sukun/{$item['file']}",
                'urutan' => $urutan++,
            ]);
        }

        // 5. TASYDID (Materi tambahan sesuai modul)
        $contohTasydid = [
            ['judul' => 'Tasydid: Abba', 'huruf' => 'أَبَّ', 'file' => 'abba.png', 'desc' => 'Alif Fathah bertemu Ba Fathah Tasydid.'],
            ['judul' => 'Tasydid: Ahhi', 'huruf' => 'أَحِّ', 'file' => 'ahhi.png', 'desc' => 'Alif Fathah bertemu Ha Kasrah Tasydid.'],
            ['judul' => 'Tasydid: Ajja', 'huruf' => 'أَجَّ', 'file' => 'ajja.png', 'desc' => 'Alif Fathah bertemu Jim Fathah Tasydid.'],
            ['judul' => 'Tasydid: Assa', 'huruf' => 'أَثَّ', 'file' => 'assa.png', 'desc' => 'Alif Fathah bertemu Tsa Fathah Tasydid.'],
            ['judul' => 'Tasydid: Atta', 'huruf' => 'أَتَّ', 'file' => 'atta.png', 'desc' => 'Alif Fathah bertemu Ta Fathah Tasydid.'],
        ];

        foreach ($contohTasydid as $item) {
            Material::create([
                'module_id' => $iqra3->id,
                'user_id' => 1,
                'judul_materi' => $item['judul'],
                'huruf_hijaiyah' => $item['huruf'],
                'category_id' => $getCategoryId('tasydid'),
                'deskripsi' => $item['desc'],
                'file_path' => "materi/iqra3/tasydid/{$item['file']}",
                'urutan' => $urutan++,
            ]);
        }

        $this->command->info("✅ Sukses! Hamzah di awal, Alif dihapus, dan Tanwin otomatis sesuai gambar.");
    }
}
