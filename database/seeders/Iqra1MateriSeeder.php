<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Material;
use App\Models\Module;

class Iqra1MateriSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * Seeder Iqra 1: Huruf Hijaiah (1-32)
     * Update: Menggunakan link video dengan timestamp terbaru.
     */
    public function run(): void
    {
        // 1. Cek Modul
        $iqra1 = Module::where('nama_modul', 'Iqra 1')->first();
        
        if (!$iqra1) {
            $this->command->error('Modul Iqra 1 tidak ditemukan!');
            return;
        }

        // 2. Daftar Data 
        // Nama file gambar (1.png - 32.png) akan otomatis mengikuti urutan array ini
        $hurufHijaiyah = [
            ['name' => 'Alif', 'symbol' => 'ا', 'desc' => 'Telapak tangan menghadap ke kiri. Jari-jari menggenggam, kecuali ibu jari menghadap ke luar, lurus menunjuk ke atas.', 'video' => 'https://youtu.be/QACK81j2nqw?start=13&end=22'],
            ['name' => 'Ba', 'symbol' => 'ب', 'desc' => 'Telapak tangan menghadap ke luar. Jari telunjuk lurus menunjuk ke atas. Mengisyaratkan satu titik.', 'video' => 'https://youtu.be/QACK81j2nqw?start=23&end=32'],
            ['name' => 'Ta', 'symbol' => 'ت', 'desc' => 'Telapak tangan menghadap ke luar. Jari telunjuk dan jari tengah rapat lurus menunjuk ke atas (dua titik).', 'video' => 'https://youtu.be/QACK81j2nqw?start=33&end=42'],
            ['name' => 'Tsa', 'symbol' => 'ث', 'desc' => 'Telapak tangan menghadap ke luar. Tiga jari (telunjuk, tengah, manis) rapat lurus menunjuk ke atas (tiga titik).', 'video' => 'https://youtu.be/QACK81j2nqw?start=41&end=50'],
            ['name' => 'Jim', 'symbol' => 'ج', 'desc' => 'Telapak tangan menghadap ke kiri. Empat jari menekuk siku-siku. Ibu jari menekuk di bawah jari lain.', 'video' => 'https://youtu.be/QACK81j2nqw?start=51&end=61'],
            ['name' => 'Ha', 'symbol' => 'ح', 'desc' => 'Telapak tangan menghadap ke kiri. Empat jari menekuk siku-siku. Ibu jari merapat pada pangkal jari telunjuk.', 'video' => 'https://youtu.be/QACK81j2nqw?start=61&end=71'],
            ['name' => 'Kha', 'symbol' => 'خ', 'desc' => 'Telapak tangan menghadap ke kiri. Empat jari menekuk siku-siku. Ibu jari lurus ke atas menghadap luar.', 'video' => 'https://youtu.be/QACK81j2nqw?start=71&end=81'],
            ['name' => 'Dal', 'symbol' => 'د', 'desc' => 'Telapak tangan menghadap ke kiri. Ibu jari dan jari telunjuk lurus menunjuk ke kiri.', 'video' => 'https://youtu.be/QACK81j2nqw?start=83&end=92'],
            ['name' => 'Dzal', 'symbol' => 'ذ', 'desc' => 'Seperti Dal, namun jari tengah bertumpu di atas jari telunjuk (satu titik di atas).', 'video' => 'https://youtu.be/QACK81j2nqw?start=93&end=102'],
            ['name' => 'Ra', 'symbol' => 'ر', 'desc' => 'Telapak tangan menghadap ke kiri. Jari telunjuk melengkung membentuk huruf ra.', 'video' => 'https://youtu.be/QACK81j2nqw?start=103&end=112'],
            ['name' => 'Zai', 'symbol' => 'ز', 'desc' => 'Seperti Ra, namun jari tengah bertumpu di atas jari telunjuk (satu titik di atas).', 'video' => 'https://youtu.be/QACK81j2nqw?start=113&end=122'],
            ['name' => 'Sin', 'symbol' => 'س', 'desc' => 'Telapak tangan menghadap ke luar. Jari-jari rapat dan lurus menunjuk ke atas (gigi sin).', 'video' => 'https://youtu.be/QACK81j2nqw?start=122&end=130'],
            ['name' => 'Syin', 'symbol' => 'ش', 'desc' => 'Telapak tangan menghadap ke luar. Jari-jari renggang dan lurus menunjuk ke atas.', 'video' => 'https://youtu.be/QACK81j2nqw?start=131&end=140'],
            ['name' => 'Shad', 'symbol' => 'ص', 'desc' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam dengan ibu jari pada posisi terluar.', 'video' => 'https://youtu.be/QACK81j2nqw?start=141&end=150'],
            ['name' => 'Dhad', 'symbol' => 'ض', 'desc' => 'Telapak tangan menghadap ke luar. Ibu jari menunjuk ke kiri (satu titik).', 'video' => 'https://youtu.be/QACK81j2nqw?start=151&end=160'],
            ['name' => 'Tha', 'symbol' => 'ط', 'desc' => 'Jari kelingking & manis menggenggam, tengah & ibu jari bertemu, telunjuk lurus ke atas.', 'video' => 'https://youtu.be/QACK81j2nqw?start=161&end=170'],
            ['name' => 'Zha', 'symbol' => 'ظ', 'desc' => 'Seperti Tha, namun ibu jari bertumpu di atas jari tengah (titik di atas).', 'video' => 'https://youtu.be/QACK81j2nqw?start=171&end=180'],
            ['name' => 'Ain', 'symbol' => 'ع', 'desc' => 'Telapak ke dalam. Telunjuk & tengah lurus ke kiri, ibu jari di ruas jari tersebut.', 'video' => 'https://youtu.be/QACK81j2nqw?start=181&end=190'],
            ['name' => 'Ghain', 'symbol' => 'غ', 'desc' => 'Seperti Ain, namun ibu jari menunjuk ke atas (titik di atas).', 'video' => 'https://youtu.be/QACK81j2nqw?start=191&end=202'],
            ['name' => 'Fa', 'symbol' => 'ف', 'desc' => 'Jari menggenggam, ujung telunjuk melengkung bertemu ujung ibu jari (lingkaran).', 'video' => 'https://youtu.be/QACK81j2nqw?start=203&end=212'],
            ['name' => 'Qaf', 'symbol' => 'ق', 'desc' => 'Jari menggenggam, ujung telunjuk & tengah melengkung bertemu ujung ibu jari.', 'video' => 'https://youtu.be/QACK81j2nqw?start=213&end=222'],
            ['name' => 'Kaf', 'symbol' => 'ك', 'desc' => 'Jari lurus rapat ke atas, ibu jari dilipat menempel telapak tangan.', 'video' => 'https://youtu.be/QACK81j2nqw?start=221&end=230'],
            ['name' => 'Lam', 'symbol' => 'ل', 'desc' => 'Telunjuk lurus ke atas, ibu jari menunjuk ke kiri (membentuk L).', 'video' => 'https://youtu.be/QACK81j2nqw?start=231&end=240'],
            ['name' => 'Mim', 'symbol' => 'م', 'desc' => 'Jari menggenggam, jari kelingking tegak menunjuk ke atas.', 'video' => 'https://youtu.be/QACK81j2nqw?start=241&end=249'],
            ['name' => 'Nun', 'symbol' => 'ن', 'desc' => 'Jari telunjuk dan ibu jari melengkung menghadap ke atas (membentuk mangkuk).', 'video' => 'https://youtu.be/QACK81j2nqw?start=250&end=259'],
            ['name' => 'Waw', 'symbol' => 'و', 'desc' => 'Jari melengkung lingkaran kecil, ibu jari menunjuk ke bawah.', 'video' => 'https://youtu.be/QACK81j2nqw?start=260&end=268'],
            ['name' => 'Ha', 'symbol' => 'ه', 'desc' => 'Keempat jari bertemu ujung ibu jari, melengkung membuat lingkaran kecil.', 'video' => 'https://youtu.be/QACK81j2nqw?start=269&end=278'],
            ['name' => 'Hamzah', 'symbol' => 'ء', 'desc' => 'Jari telunjuk menunjuk ke atas melukiskan huruf hamzah di udara.', 'video' => 'https://youtu.be/QACK81j2nqw?start=289&end=298'],
            ['name' => 'Ya', 'symbol' => 'ي', 'desc' => 'Ibu jari dan kelingking menunjuk ke atas dan direnggangkan.', 'video' => 'https://youtu.be/QACK81j2nqw?start=299&end=308'], 
            ['name' => 'Alif Maqsurah', 'symbol' => 'ى', 'desc' => 'Seperti Ya, sambil menggerakkan pergelangan tangan ke dalam dua kali.', 'video' => 'https://youtu.be/QACK81j2nqw?start=308&end=317'], 
            ['name' => 'Ta Marbutah', 'symbol' => 'ة', 'desc' => 'Telunjuk & tengah melengkung renggang (dua titik).', 'video' => 'https://youtu.be/QACK81j2nqw?start=317&end=326'],    
            ['name' => 'Lam Alif', 'symbol' => 'لا', 'desc' => 'Isyarat Lam dan Alif dilakukan terpisah berurutan.', 'video' => 'https://youtu.be/QACK81j2nqw?start=279&end=287'], 
            ];

        $this->command->info('Seeding materi Iqra 1 (Auto-mapping files 1.png - 32.png & New Videos)...');
        
        foreach ($hurufHijaiyah as $index => $huruf) {
            $nomorFile = $index + 1; 
            $fileName = $nomorFile . '.png';

            Material::create([
                'module_id' => $iqra1->id,
                'user_id' => 1,
                'judul_materi' => 'Huruf ' . $huruf['name'],
                'huruf_hijaiyah' => $huruf['symbol'],
                'category_id' => null,
                'deskripsi' => $huruf['desc'],
                'file_video' => $huruf['video'],
                'file_path' => 'materi/iqra1/' . $fileName, 
                'urutan' => $nomorFile,
            ]);

            $this->command->info("✓ Created: {$huruf['name']} -> Img: {$fileName} | Vid: {$huruf['video']}");
        }

        $this->command->info("✅ Sukses! 32 Materi telah diperbarui dengan video terbaru.");
    }
}
