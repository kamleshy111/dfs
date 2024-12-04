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
        Schema::table('vehicles', function (Blueprint $table) {
            $table->bigInteger('device_id')->default(0)->after('fuel_type');
            $table->string('vehicle_register_number')->nullable()->after('device_id');
            $table->string('vehicle_name')->nullable()->after('vehicle_register_number');
            $table->string('vehicle_type')->nullable()->after('vehicle_name');
            $table->string('imei_number')->nullable()->after('vehicle_type');
            $table->string('sim_card_number')->nullable()->after('imei_number');
            $table->date('installation_date')->nullable()->after('sim_card_number');
            $table->date('start_date')->nullable()->after('installation_date');
            $table->string('duration')->nullable()->after('start_date');
            $table->string('sim_operator')->nullable()->after('duration');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn([
                'device_id',
                'vehicle_register_number',
                'vehicle_name',
                'vehicle_type',
                'imei_number',
                'sim_card_number',
                'installation_date',
                'start_date',
                'duration',
                'sim_operator',
            ]);
        });
    }
};
