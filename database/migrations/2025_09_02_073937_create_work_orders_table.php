<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    // database/migrations/xxxx_xx_xx_xxxxxx_create_work_orders_table.php
public function up()
{
    Schema::create('work_orders', function (Blueprint $table) {
        $table->id();
        $table->foreignId('rental_request_id')->constrained();
        $table->string('operator_name');
        $table->text('assignment_letter')->nullable(); // Surat perintah tugas
        $table->enum('status', ['pending', 'in_progress', 'completed'])->default('pending');
        $table->timestamps();
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
