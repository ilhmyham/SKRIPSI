<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Materi;
use App\Models\ModulIqra;

class HijaiyahIqra1Seeder extends Seeder
{
    /**
     * Run the database seeds.
     * * Seeder untuk huruf hijaiyah dasar (tanpa harakat) di Iqra 1
     * Berdasarkan Panduan Belajar Membaca Mushaf Al-Qur'an Isyarat (LPMQ Kemenag)
     */
    public function run(): void
    {
        // Get Iqra 1 module
        $iqra1 = ModulIqra::where('nama_modul', 'Iqra 1')->first();
        
        if (!$iqra1) {
            $this->command->error('Module Iqra 1 not found! Please run ModulIqraSeeder first.');
            return;
        }

        // Huruf hijaiyah lengkap sesuai Jilid 1 (32 entri termasuk variasi)
        $hurufHijaiyah = [
            [
                'judul' => 'Huruf Alif',
                'huruf' => 'ا', 
                'nama' => 'Alif',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Jari-jari menggenggam, kecuali ibu jari menghadap ke luar, lurus menunjuk ke atas. Bentuk seperti huruf alif.'
            ],
            [
                'judul' => 'Huruf Ba',
                'huruf' => 'ب', 
                'nama' => 'Ba',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam, kecuali jari telunjuk lurus menunjuk ke atas. Mengisyaratkan huruf ba memiliki satu titik.'
            ],
            [
                'judul' => 'Huruf Ta',
                'huruf' => 'ت', 
                'nama' => 'Ta',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam, kecuali jari telunjuk dan jari tengah rapat lurus menunjuk ke atas. Mengisyaratkan huruf ta memiliki dua titik.'
            ],
            [
                'judul' => 'Huruf Tsa',
                'huruf' => 'ث', 
                'nama' => 'Tsa',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam, kecuali jari telunjuk, jari tengah dan jari manis rapat lurus menunjuk ke atas. Mengisyaratkan huruf sa memiliki tiga titik.'
            ],
            [
                'judul' => 'Huruf Jim',
                'huruf' => 'ج', 
                'nama' => 'Jim',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Empat jari selain ibu jari menekuk membentuk sudut siku-siku. Ibu jari menekuk di bawah jari-jari yang lain.'
            ],
            [
                'judul' => 'Huruf Ha',
                'huruf' => 'ح', 
                'nama' => 'Ha',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Empat jari selain ibu jari menekuk membentuk sudut siku-siku. Ibu jari merapat pada pangkal jari telunjuk.'
            ],
            [
                'judul' => 'Huruf Kha',
                'huruf' => 'خ', 
                'nama' => 'Kha',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Empat jari selain ibu jari menekuk membentuk sudut siku-siku. Ibu jari lurus ke atas menghadap luar.'
            ],
            [
                'judul' => 'Huruf Dal',
                'huruf' => 'د', 
                'nama' => 'Dal',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Jari-jari menggenggam, kecuali ibu jari dan jari telunjuk lurus menunjuk ke kiri.'
            ],
            [
                'judul' => 'Huruf Dzal',
                'huruf' => 'ذ', 
                'nama' => 'Dzal',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Jari-jari menggenggam, kecuali ibu jari dan jari telunjuk lurus menunjuk ke kiri membentuk huruf dal, dan jari tengah bertumpu di atas jari telunjuk.'
            ],
            [
                'judul' => 'Huruf Ra',
                'huruf' => 'ر', 
                'nama' => 'Ra',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Jari-jari menggenggam, kecuali jari telunjuk melengkung, membentuk huruf ra.'
            ],
            [
                'judul' => 'Huruf Zai',
                'huruf' => 'ز', 
                'nama' => 'Zai',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Jari-jari menggenggam, kecuali jari telunjuk melengkung membentuk huruf ra, dan jari tengah bertumpu di atas jari telunjuk.'
            ],
            [
                'judul' => 'Huruf Sin',
                'huruf' => 'س', 
                'nama' => 'Sin',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari rapat dan lurus menunjuk ke atas. Jari-jari menggambarkan gigi-gigi pada huruf sin.'
            ],
            [
                'judul' => 'Huruf Syin',
                'huruf' => 'ش', 
                'nama' => 'Syin',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari renggang dan lurus menunjuk ke atas, kecuali ibu jari merapat kepada jari telunjuk.'
            ],
            [
                'judul' => 'Huruf Shad',
                'huruf' => 'ص', 
                'nama' => 'Shad',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam dengan ibu jari pada posisi terluar.'
            ],
            [
                'judul' => 'Huruf Dhad',
                'huruf' => 'ض', 
                'nama' => 'Dhad',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam dengan ibu jari menunjuk ke kiri, menunjukkan adanya satu titik pada huruf dad.'
            ],
            [
                'judul' => 'Huruf Tha',
                'huruf' => 'ط', 
                'nama' => 'Tha',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Jari kelingking dan jari manis menggenggam, ujung jari tengah dan ibu jari bertemu, sedangkan jari telunjuk lurus menunjuk ke atas.'
            ],
            [
                'judul' => 'Huruf Zha',
                'huruf' => 'ظ', 
                'nama' => 'Zha',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Jari kelingking dan manis menggenggam, jari tengah lurus ke kiri, telunjuk lurus ke atas, ibu jari bertumpu di atas jari tengah.'
            ],
            [
                'judul' => 'Huruf Ain',
                'huruf' => 'ع', 
                'nama' => 'Ain',
                'deskripsi' => 'Telapak tangan menghadap ke dalam. Jari-jari menggenggam, kecuali jari telunjuk dan jari tengah rapat menunjuk lurus ke kiri, dan ibu jari diletakan pada ruas jari telunjuk dan tengah.'
            ],
            [
                'judul' => 'Huruf Ghain',
                'huruf' => 'غ', 
                'nama' => 'Ghain',
                'deskripsi' => 'Telapak tangan menghadap ke dalam. Jari-jari menggenggam, kecuali jari telunjuk dan jari tengah rapat lurus menunjuk ke kiri dan ibu jari menunjuk ke atas menempel pada jari telunjuk.'
            ],
            [
                'judul' => 'Huruf Fa',
                'huruf' => 'ف', 
                'nama' => 'Fa',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam kecuali ujung jari telunjuk melengkung bertemu dengan ujung ibu jari.'
            ],
            [
                'judul' => 'Huruf Qaf',
                'huruf' => 'ق', 
                'nama' => 'Qaf',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam, kecuali ujung jari telunjuk dan jari tengah melengkung bertemu dengan ujung ibu jari.'
            ],
            [
                'judul' => 'Huruf Kaf',
                'huruf' => 'ك', 
                'nama' => 'Kaf',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari lurus dan rapat menunjuk ke atas, kecuali ibu jari dilipat menempel pada telapak tangan.'
            ],
            [
                'judul' => 'Huruf Lam',
                'huruf' => 'ل', 
                'nama' => 'Lam',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam, kecuali jari telunjuk lurus menunjuk ke atas dan ibu jari menunjuk ke kiri.'
            ],
            [
                'judul' => 'Huruf Mim',
                'huruf' => 'م', 
                'nama' => 'Mim',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam, kecuali jari kelingking tegak menunjuk ke atas.'
            ],
            [
                'judul' => 'Huruf Nun',
                'huruf' => 'ن', 
                'nama' => 'Nun',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam, kecuali jari telunjuk dan ibu jari melengkung menghadap ke atas, membentuk huruf nun.'
            ],
            [
                'judul' => 'Huruf Waw',
                'huruf' => 'و', 
                'nama' => 'Waw',
                'deskripsi' => 'Telapak tangan menghadap ke kiri. Jari-jari selain ibu jari rapat melengkung membuat lingkaran kecil, ujung jari menempel di ruas pangkal ibu jari bagian dalam, ibu jari menunjuk ke bawah.'
            ],
            [
                'judul' => 'Huruf Ha',
                'huruf' => 'ه', 
                'nama' => 'Ha',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Keempat jari bertemu dengan ujung ibu jari, melengkung membuat lingkaran kecil.'
            ],
            [
                'judul' => 'Huruf Lam Alif',
                'huruf' => 'لا', 
                'nama' => 'Lam Alif',
                'deskripsi' => 'Huruf lam dan alif diisyaratkan terpisah, secara berurutan dari kanan ke kiri.'
            ],
            [
                'judul' => 'Huruf Hamzah',
                'huruf' => 'ء', 
                'nama' => 'Hamzah',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam kecuali jari telunjuk menunjuk ke atas melukiskan huruf hamzah di udara.'
            ],
            [
                'judul' => 'Huruf Ya',
                'huruf' => 'ي', 
                'nama' => 'Ya',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam, kecuali ibu jari dan jari kelingking menunjuk ke atas dan direnggangkan.'
            ],
            [
                'judul' => 'Huruf Ta Marbuṭah',
                'huruf' => 'ة', 
                'nama' => 'Ta Marbuṭah',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam, kecuali jari telunjuk dan jari tengah melengkung dan renggang mengisyaratkan huruf ta marbutah memiliki dua titik di atasnya.'
            ],
            [
                'judul' => 'Huruf Alif Maqsurah',
                'huruf' => 'ى', 
                'nama' => 'Alif Maqsurah',
                'deskripsi' => 'Telapak tangan menghadap ke luar. Jari-jari menggenggam, kecuali ibu jari dan jari kelingking menunjuk ke atas, sambil menggerakkan pergelangan tangan ke dalam dua kali.'
            ],
        ];

        $this->command->info('Creating Hijaiyah materials for Iqra 1 with Sign Language descriptions...');

        foreach ($hurufHijaiyah as $huruf) {
            Materi::create([
                'judul_materi' => $huruf['judul'],
                'huruf_hijaiyah' => $huruf['huruf'],
                'deskripsi' => $huruf['deskripsi'], // Menggunakan deskripsi detail dari pedoman
                'modul_iqra_modul_id' => $iqra1->modul_id,
                'users_user_id' => 1, // Admin user
            ]);
            
            $this->command->info("✓ Created: {$huruf['judul']} - {$huruf['huruf']}");
        }

        $this->command->info('Successfully created ' . count($hurufHijaiyah) . ' Hijaiyah letters for Iqra 1!');
    }
}