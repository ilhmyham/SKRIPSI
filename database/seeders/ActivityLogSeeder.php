<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ActivityLog;
use App\Models\User;

class ActivityLogSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $admin = User::whereHas('role', fn($q) => $q->where('nama_role', 'admin'))->first();
        $guru = User::whereHas('role', fn($q) => $q->where('nama_role', 'guru'))->first();

        if (!$admin) {
            echo "No admin user found. Please run UserSeeder first.\n";
            return;
        }

        // Sample activity logs
        $activities = [
            [
                'user_id' => $admin->id,
                'activity_type' => 'created',
                'subject_type' => 'User',
                'subject_id' => 2,
                'description' => 'Menambahkan pengguna baru "Siti Aminah" sebagai siswa',
                'created_at' => now()->subMinutes(5),
            ],
            [
                'user_id' => $admin->id,
                'activity_type' => 'updated',
                'subject_type' => 'ModulIqra',
                'subject_id' => 1,
                'description' => 'Mengupdate modul "Iqra 1"',
                'created_at' => now()->subMinutes(10),
            ],
            [
                'user_id' => $admin->id,
                'activity_type' => 'created',
                'subject_type' => 'ModulIqra',
                'subject_id' => 2,
                'description' => 'Menambahkan modul "Iqra 2"',
                'created_at' => now()->subMinutes(15),
            ],
        ];

        if ($guru) {
            $activities = array_merge($activities, [
                [
                    'user_id' => $guru->id,
                    'activity_type' => 'created',
                    'subject_type' => 'Materi',
                    'subject_id' => 1,
                    'description' => 'Menambahkan materi "Huruf Alif" ke Iqra 1',
                    'created_at' => now()->subMinutes(20),
                ],
                [
                    'user_id' => $guru->id,
                    'activity_type' => 'deleted',
                    'subject_type' => 'Materi',
                    'subject_id' => 5,
                    'description' => 'Menghapus materi "Huruf Ba"',
                    'created_at' => now()->subMinutes(25),
                ],
                [
                    'user_id' => $guru->id,
                    'activity_type' => 'created',
                    'subject_type' => 'Kuis',
                    'subject_id' => 1,
                    'description' => 'Membuat kuis "Latihan Huruf Alif" untuk Iqra 1',
                    'created_at' => now()->subMinutes(30),
                ],
            ]);
        }

        foreach ($activities as $activity) {
            ActivityLog::create($activity);
        }

        echo "Activity logs created successfully!\n";
    }
}
