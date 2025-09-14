<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('heavy_equipments', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Nama alat berat
            $table->text('description')->nullable(); // Boleh kosong
            $table->decimal('price_per_day', 15, 2); // Harga sewa per hari
            $table->string('image')->nullable(); // Foto alat berat
            $table->boolean('availability')->default(true); // True = tersedia
            $table->json('available_dates')->nullable(); // Tanggal yang bisa dibooking
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heavy_equipments');
    }
};
