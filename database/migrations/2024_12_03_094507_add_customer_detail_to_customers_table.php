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
            $table->string('secondary_email')->nullable()->after('phone');
            $table->string('secondary_phone')->nullable()->after('secondary_email');
            $table->string('invoice_number')->nullable()->after('secondary_phone');
            $table->decimal('amount', 10, 2)->default(0)->after('invoice_number');
            $table->string('payment_status')->nullable()->after('amount');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn('secondary_email');
            $table->dropColumn('secondary_phone');
            $table->dropColumn('invoice_number');
            $table->dropColumn('amount');
            $table->dropColumn('payment_status');
        });
    }
};
