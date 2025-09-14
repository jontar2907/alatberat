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
        Schema::table('heavy_equipments', function (Blueprint $table) {
            // Tambahkan kolom status
            if (!Schema::hasColumn('heavy_equipments', 'status')) {
                $table->enum('status', ['available', 'unavailable'])
                      ->default('available')
                      ->after('image');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('heavy_equipments', function (Blueprint $table) {
            // Hapus kolom status kalau ada rollback
            if (Schema::hasColumn('heavy_equipments', 'status')) {
                $table->dropColumn('status');
            }
        });
    }
};
