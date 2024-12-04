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
            $table->dropColumn([
                'company_name',
                'model',
                'fuel_type',
                'Chassis_number',
                'color',
                'driver_name',
                'license_number',
                'license_expiry_date',
                'driver_contact',
                'driver_address',
                'year'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('company_name')->nullable();
            $table->string('model')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('Chassis_number')->unique();
            $table->string('color')->nullable();
            $table->string('driver_name')->nullable();
            $table->string('license_number')->unique()->nullable();
            $table->date('license_expiry_date')->nullable();
            $table->string('driver_contact')->nullable();
            $table->string('driver_address')->nullable();
            $table->date('year')->nullable();
        });
    }
};