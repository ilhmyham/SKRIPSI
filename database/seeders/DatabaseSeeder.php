<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            ModuleSeeder::class,
            KategoriSeeder::class,
            Iqra1MateriSeeder::class,
            Iqra2MateriSeeder::class,
            Iqra3MateriSeeder::class,
            Iqra4StrategisSeeder::class,
            Iqra5MateriSeeder::class,
            Iqra6MateriSeeder::class,
        ]);
    }
}
