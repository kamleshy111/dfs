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
        Schema::table('alerts', function (Blueprint $table) {
            $table->tinyInteger('user_re_un_status')->default(0)->after('read_unread_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('alerts', function (Blueprint $table) {
            $table->dropColumn('user_read_unread_status');

        });
    }
};
