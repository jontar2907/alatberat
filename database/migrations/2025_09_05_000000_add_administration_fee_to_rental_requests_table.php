<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
Schema::table('rental_requests', function (Blueprint $table) {
    $table->decimal('administration_fee', 15, 2)->default(0);
});
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::table('rental_requests', function (Blueprint $table) {
            $table->dropColumn('administration_fee');
        });
    }
};
