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
        // Panggil seeder HeavyEquipmentSeeder
        $this->call(HeavyEquipmentSeeder::class);

        // Seed admin user
        $this->call(\Database\Seeders\AdminUserSeeder::class);
    }
}
