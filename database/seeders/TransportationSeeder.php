<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Transportation;

class TransportationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $transportations = [
            ['name' => 'jemput sendiri', 'description' => 'Penjemputan dilakukan sendiri oleh penyewa.', 'cost' => 0],
            ['name' => 'diantar oleh dinas', 'description' => 'Transportasi diantar oleh dinas terkait.', 'cost' => 500000], // example cost
        ];

        foreach ($transportations as $transportation) {
            Transportation::updateOrCreate(['name' => $transportation['name']], $transportation);
        }
    }
}
