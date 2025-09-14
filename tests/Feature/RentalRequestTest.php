<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\RentalRequest;
use App\Models\HeavyEquipment;
use App\Models\Transportation;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RentalRequestTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test successful rental request creation
     */
    public function test_successful_rental_request_creation()
    {
        // Create test data
        $equipment = HeavyEquipment::factory()->create();
        $transportation = Transportation::factory()->create();

        $data = [
            'heavy_equipment_id' => $equipment->id,
            'full_name' => 'John Doe',
            'nik' => '1234567890123456',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
            'email' => 'john@example.com',
            'work_location' => 'Test Location',
            'work_purpose' => 'Test Purpose',
            'jumlah_pemakaian' => 5,
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
            'transportasi' => $transportation->id,
            'transportation_cost' => 100000,
        ];

        $response = $this->post(route('rental.store'), $data);

        $response->assertRedirect(route('landing'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('rental_requests', [
            'heavy_equipment_id' => $equipment->id,
            'nik' => '1234567890123456',
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
        ]);
    }

    /**
     * Test duplicate rental request prevention with overlapping dates
     */
    public function test_duplicate_rental_request_prevention_overlapping_dates()
    {
        // Create test data
        $equipment = HeavyEquipment::factory()->create();
        $transportation = Transportation::factory()->create();

        // Create first rental request
        RentalRequest::create([
            'heavy_equipment_id' => $equipment->id,
            'full_name' => 'John Doe',
            'nik' => '1234567890123456',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
            'email' => 'john@example.com',
            'work_location' => 'Test Location',
            'work_purpose' => 'Test Purpose',
            'jumlah_pemakaian' => 5,
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
            'transportasi' => $transportation->name,
            'transportation_cost' => 100000,
            'status' => 'pending',
            'total_cost' => 500000,
        ]);

        // Try to create duplicate with overlapping dates
        $data = [
            'heavy_equipment_id' => $equipment->id,
            'full_name' => 'Jane Doe',
            'nik' => '1234567890123456', // Same NIK
            'address' => 'Test Address 2',
            'phone_number' => '081234567891',
            'email' => 'jane@example.com',
            'work_location' => 'Test Location 2',
            'work_purpose' => 'Test Purpose 2',
            'jumlah_pemakaian' => 3,
            'start_date' => '2025-01-03', // Overlapping date
            'end_date' => '2025-01-05',
            'transportasi' => $transportation->id,
            'transportation_cost' => 50000,
        ];

        $response = $this->post(route('rental.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['heavy_equipment_id' => 'Anda sudah memesan alat ini pada tanggal yang sama.']);

        // Ensure no duplicate record was created
        $this->assertDatabaseCount('rental_requests', 1);
    }

    /**
     * Test duplicate rental request prevention with exact same dates
     */
    public function test_duplicate_rental_request_prevention_exact_same_dates()
    {
        // Create test data
        $equipment = HeavyEquipment::factory()->create();
        $transportation = Transportation::factory()->create();

        // Create first rental request
        RentalRequest::create([
            'heavy_equipment_id' => $equipment->id,
            'full_name' => 'John Doe',
            'nik' => '1234567890123456',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
            'email' => 'john@example.com',
            'work_location' => 'Test Location',
            'work_purpose' => 'Test Purpose',
            'jumlah_pemakaian' => 5,
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
            'transportasi' => $transportation->name,
            'transportation_cost' => 100000,
            'status' => 'pending',
            'total_cost' => 500000,
        ]);

        // Try to create duplicate with exact same dates
        $data = [
            'heavy_equipment_id' => $equipment->id,
            'full_name' => 'Jane Doe',
            'nik' => '1234567890123456', // Same NIK
            'address' => 'Test Address 2',
            'phone_number' => '081234567891',
            'email' => 'jane@example.com',
            'work_location' => 'Test Location 2',
            'work_purpose' => 'Test Purpose 2',
            'jumlah_pemakaian' => 5,
            'start_date' => '2025-01-01', // Exact same dates
            'end_date' => '2025-01-05',
            'transportasi' => $transportation->id,
            'transportation_cost' => 100000,
        ];

        $response = $this->post(route('rental.store'), $data);

        $response->assertRedirect();
        $response->assertSessionHasErrors(['heavy_equipment_id' => 'Anda sudah memesan alat ini pada tanggal yang sama.']);

        // Ensure no duplicate record was created
        $this->assertDatabaseCount('rental_requests', 1);
    }

    /**
     * Test allowing rental request with non-overlapping dates
     */
    public function test_allow_rental_request_with_non_overlapping_dates()
    {
        // Create test data
        $equipment = HeavyEquipment::factory()->create();
        $transportation = Transportation::factory()->create();

        // Create first rental request
        RentalRequest::create([
            'heavy_equipment_id' => $equipment->id,
            'full_name' => 'John Doe',
            'nik' => '1234567890123456',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
            'email' => 'john@example.com',
            'work_location' => 'Test Location',
            'work_purpose' => 'Test Purpose',
            'jumlah_pemakaian' => 5,
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
            'transportasi' => $transportation->name,
            'transportation_cost' => 100000,
            'status' => 'pending',
            'total_cost' => 500000,
        ]);

        // Create second rental request with non-overlapping dates
        $data = [
            'heavy_equipment_id' => $equipment->id,
            'full_name' => 'John Doe',
            'nik' => '1234567890123456', // Same NIK
            'address' => 'Test Address',
            'phone_number' => '081234567890',
            'email' => 'john@example.com',
            'work_location' => 'Test Location',
            'work_purpose' => 'Test Purpose',
            'jumlah_pemakaian' => 5,
            'start_date' => '2025-01-10', // Non-overlapping dates
            'end_date' => '2025-01-14',
            'transportasi' => $transportation->id,
            'transportation_cost' => 100000,
        ];

        $response = $this->post(route('rental.store'), $data);

        $response->assertRedirect(route('landing'));
        $response->assertSessionHas('success');

        // Ensure second record was created
        $this->assertDatabaseCount('rental_requests', 2);
    }

    /**
     * Test allowing rental request after rejected status
     */
    public function test_allow_rental_request_after_rejected_status()
    {
        // Create test data
        $equipment = HeavyEquipment::factory()->create();
        $transportation = Transportation::factory()->create();

        // Create first rental request with rejected status
        RentalRequest::create([
            'heavy_equipment_id' => $equipment->id,
            'full_name' => 'John Doe',
            'nik' => '1234567890123456',
            'address' => 'Test Address',
            'phone_number' => '081234567890',
            'email' => 'john@example.com',
            'work_location' => 'Test Location',
            'work_purpose' => 'Test Purpose',
            'jumlah_pemakaian' => 5,
            'start_date' => '2025-01-01',
            'end_date' => '2025-01-05',
            'transportasi' => $transportation->name,
            'transportation_cost' => 100000,
            'status' => 'rejected', // Rejected status
            'total_cost' => 500000,
        ]);

        // Try to create new rental request with overlapping dates
        $data = [
            'heavy_equipment_id' => $equipment->id,
            'full_name' => 'John Doe',
            'nik' => '1234567890123456', // Same NIK
            'address' => 'Test Address',
            'phone_number' => '081234567890',
            'email' => 'john@example.com',
            'work_location' => 'Test Location',
            'work_purpose' => 'Test Purpose',
            'jumlah_pemakaian' => 5,
            'start_date' => '2025-01-01', // Same dates
            'end_date' => '2025-01-05',
            'transportasi' => $transportation->id,
            'transportation_cost' => 100000,
        ];

        $response = $this->post(route('rental.store'), $data);

        $response->assertRedirect(route('landing'));
        $response->assertSessionHas('success');

        // Ensure new record was created
        $this->assertDatabaseCount('rental_requests', 2);
    }
}
