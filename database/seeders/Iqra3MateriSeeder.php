<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class Iqra3MateriSeeder extends Seeder
{
    public function run(): void
    {
        $iqra3 = Module::where('nama_modul', 'Iqra 3')->first();
        
        if (!$iqra3) {
            $this->command->error('Modul Iqra 3 tidak ditemukan!');
            return;
        }

        $getCategoryId = function($kategori) use ($iqra3) {
            $slug = Str::slug($kategori, '_');
            return DB::table('material_categories')
                ->where('module_id', $iqra3->id)
                ->where('nama', $slug)
                ->value('id');
        };

        // Daftar Huruf Dasar untuk Tanwin
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

        // 1. SEGMEN TANWIN
        $tanwinConfig = [
            ['nama' => 'fathatain', 'folder' => 'fathah_tanwin', 'suffix' => 'an', 'label' => 'Fathatain'],
            ['nama' => 'kasratain', 'folder' => 'kasrah_tanwin', 'suffix' => 'in', 'label' => 'Kasratain'],
            ['nama' => 'dammatain', 'folder' => 'dammah_tanwin', 'suffix' => 'un', 'label' => 'Dammatain'],
        ];

        foreach ($tanwinConfig as $conf) {
            $urutan = 1; // Reset urutan per segmen tanwin
            foreach ($listHuruf as $h) {
                $vokal = ($h['slug'] == 'hamzah') ? $conf['suffix'] : $h['bunyi'] . $conf['suffix'];
                
                // Penyesuaian nama file gambar khusus
                if ($h['slug'] == 'ha_besar') {
                    $fileName = $h['bunyi'] . $conf['suffix'] . '_besar.png';
                } elseif ($h['slug'] == 'ta_marbutah') {
                    $fileName = $h['bunyi'] . $conf['suffix'] . '_marbutah.png';
                } else {
                    $fileName = $vokal . '.png';
                }

                // Logika Simbol Hijaiyah
                $simbol = match($conf['nama']) {
                    'fathatain' => 'ً',
                    'kasratain' => 'ٍ',
                    'dammatain' => 'ٌ',
                };

                $hurufFinal = $h['arab'] . $simbol;
                if ($conf['nama'] == 'fathatain' && $h['slug'] !== 'ta_marbutah' && $h['slug'] !== 'hamzah') {
                    $hurufFinal .= 'ا';
                }

                Material::create([
                    'module_id' => $iqra3->id,
                    'user_id' => 1,
                    'judul_materi' => "{$conf['label']}: {$h['name']} ({$vokal})",
                    'huruf_hijaiyah' => $hurufFinal,
                    'category_id' => $getCategoryId($conf['nama']),
                    'deskripsi' => "Huruf {$h['name']} berharakat {$conf['label']}, dibaca '{$vokal}'.",
                    'file_path' => "materi/iqra3/{$conf['folder']}/{$fileName}",
                    'urutan' => $urutan++,
                ]);
            }
            $this->command->info("✓ Selesai kategori: " . $conf['label']);
        }

        // 2. SEGMEN SUKUN
        $urutanSukun = 1; // Reset urutan untuk sukun
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
                'urutan' => $urutanSukun++,
            ]);
        }
        $this->command->info("✓ Selesai kategori: Sukun");

        // 3. SEGMEN TASYDID
        $urutanTasydid = 1; // Reset urutan untuk tasydid
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
                'urutan' => $urutanTasydid++,
            ]);
        }
        $this->command->info("✓ Selesai kategori: Tasydid");

        $this->command->info("✅ SELESAI. Materi Iqra 3 Berhasil Dibuat dengan urutan per kategori.");
    }
}