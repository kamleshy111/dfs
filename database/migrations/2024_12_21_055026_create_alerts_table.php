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
        Schema::create('alerts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('device_id');
            $table->string('latitude');
            $table->string('longitude');
            $table->text('location');
            $table->enum('status', ['active', 'inactive', 'expired'])->default('inactive');
            $table->tinyInteger('read_unread_status')->default(0);
            $table->string('captures');
            $table->text('message');
            $table->string('alert_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('alerts');
    }
};
