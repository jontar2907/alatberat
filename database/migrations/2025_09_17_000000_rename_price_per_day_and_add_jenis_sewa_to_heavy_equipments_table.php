<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenamePricePerDayAndAddJenisSewaToHeavyEquipmentsTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, rename the column
        Schema::table('heavy_equipments', function (Blueprint $table) {
            if (Schema::hasColumn('heavy_equipments', 'price_per_day')) {
                $table->renameColumn('price_per_day', 'price');
            }
        });

        // Then, add the new column after the renamed column
        Schema::table('heavy_equipments', function (Blueprint $table) {
            if (!Schema::hasColumn('heavy_equipments', 'jenis_sewa')) {
                $table->string('jenis_sewa')->default('Perhari')->after('price');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('heavy_equipments', function (Blueprint $table) {
            // Rename price back to price_per_day
            if (Schema::hasColumn('heavy_equipments', 'price')) {
                $table->renameColumn('price', 'price_per_day');
            }
            // Drop jenis_sewa column
            if (Schema::hasColumn('heavy_equipments', 'jenis_sewa')) {
                $table->dropColumn('jenis_sewa');
            }
        });
    }
}
