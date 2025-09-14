<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HeavyEquipment;
use App\Models\RentalRequest;
use App\Models\Payment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentSeeder extends Seeder
{
    public function run()
    {
        // Clear existing data
        DB::table('payments')->truncate();
        DB::table('rental_requests')->truncate();
        DB::table('heavy_equipments')->truncate();

        // Create some heavy equipment
        $equipment1 = HeavyEquipment::create([
            'name' => 'Excavator',
            'description' => 'Excavator for heavy digging',
            'price_per_day' => 1000000,
            'availability' => true,
            'image' => null,
        ]);

        $equipment2 = HeavyEquipment::create([
            'name' => 'Bulldozer',
            'description' => 'Bulldozer for land clearing',
            'price_per_day' => 1500000,
            'availability' => true,
            'image' => null,
        ]);

        // Create rental requests
        $rental1 = RentalRequest::create([
            'heavy_equipment_id' => $equipment1->id,
            'full_name' => 'John Doe',
            'email' => 'john@example.com',
            'start_date' => now()->addDays(1),
            'end_date' => now()->addDays(3),
            'total_cost' => 3000000,
            'status' => 'payment_pending',
        ]);

        $rental2 = RentalRequest::create([
            'heavy_equipment_id' => $equipment2->id,
            'full_name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'start_date' => now()->addDays(2),
            'end_date' => now()->addDays(5),
            'total_cost' => 6000000,
            'status' => 'payment_pending',
        ]);

        // Create payments
        Payment::create([
            'rental_request_id' => $rental1->id,
            'amount' => $rental1->total_cost,
            'payment_proof' => null,
            'status' => 'pending',
        ]);

        Payment::create([
            'rental_request_id' => $rental2->id,
            'amount' => $rental2->total_cost,
            'payment_proof' => null,
            'status' => 'verified',
            'verified_at' => now(),
        ]);
    }
}
