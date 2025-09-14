<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_rental_requests_table.php
public function up()
{
    Schema::create('rental_requests', function (Blueprint $table) {
        $table->id();
        $table->foreignId('heavy_equipment_id')->constrained();
        $table->string('full_name');
        $table->string('nik', 16);
        $table->text('address');
        $table->string('phone_number', 15);
        $table->string('email');
        $table->string('work_location');
        $table->text('work_purpose');
        $table->integer('days_count');
        $table->date('start_date');
        $table->date('end_date');
        $table->decimal('total_cost', 15, 2);
        $table->enum('status', ['pending', 'approved', 'rejected', 'payment_pending', 'payment_verified', 'completed'])->default('pending');
        $table->timestamps();
    });
}
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rental_requests');
    }
};
