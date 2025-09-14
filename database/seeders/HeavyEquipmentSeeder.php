<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeavyEquipment;

class HeavyEquipmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        HeavyEquipment::create([
            'name' => 'Excavator',
            'description' => 'Excavator serbaguna untuk penggalian dan konstruksi.',
            'price' => 2500000,
            'jenis_sewa' => 'Perhari',
            'image' => null, // bisa diisi nama file gambar kalau sudah upload
            'availability' => true,
            'available_dates' => json_encode(['2025-09-05', '2025-09-07', '2025-09-10']),
        ]);

        HeavyEquipment::create([
            'name' => 'Bulldozer',
            'description' => 'Bulldozer untuk meratakan tanah dan pekerjaan berat.',
            'price' => 3000000,
            'jenis_sewa' => 'Perhari',
            'image' => null,
            'availability' => true,
            'available_dates' => json_encode(['2025-09-06', '2025-09-08', '2025-09-12']),
        ]);

        HeavyEquipment::create([
            'name' => 'Crane',
            'description' => 'Crane untuk mengangkat material berat di proyek konstruksi.',
            'price' => 5000000,
            'jenis_sewa' => 'Perhari',
            'image' => null,
            'availability' => true,
            'available_dates' => json_encode(['2025-09-09', '2025-09-11', '2025-09-13']),
        ]);
    }
}
