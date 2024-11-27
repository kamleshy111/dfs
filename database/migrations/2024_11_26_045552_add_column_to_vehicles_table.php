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
            $table->bigInteger('customer_id')->nullable()->after('id');
            $table->string('driver_name')->nullable()->after('color');
            $table->string('license_number')->unique()->nullable()->after('driver_name');
            $table->date('license_expiry_date')->nullable()->after('license_number');
            $table->string('driver_contact')->nullable()->after('license_expiry_date');
            $table->string('driver_address')->nullable()->after('driver_contact');
            $table->date('year')->nullable()->after('driver_address');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn('driver_name');
            $table->dropColumn('license_number');
            $table->dropColumn('license_expiry_date');
            $table->dropColumn('driver_contact');
            $table->dropColumn('driver_address');
            $table->dropColumn('year');
        });
    }
};
