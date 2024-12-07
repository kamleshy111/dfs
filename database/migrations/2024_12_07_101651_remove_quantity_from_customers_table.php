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
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn([
                'quantity',
                'invoice_number',
                'amount',
                'payment_status',
                'password',
                'booking_id',
                'profile_picture',
                'date_of_birth',
                'device_id',
                'file'
            ]);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->integer('quantity')->default(0);
            $table->string('invoice_number')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->string('payment_status')->nullable();
            $table->string('password')->nullable();
            $table->string('booking_id')->unique()->nullable();
            $table->string('profile_picture')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->string('device_id')->unique()->nullable();
            $table->text('file')->nullable();
        });
    }
};
