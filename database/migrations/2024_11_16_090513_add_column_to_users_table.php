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
        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->after('password');
            $table->date('birth_date')->nullable()->after('password');
            $table->enum('gender', ['male', 'female', 'other'])->nullable()->after('password');
            $table->string('profile_image')->nullable()->after('password');
            $table->string('address')->nullable()->after('password');
            $table->boolean('is_active')->default(false);
            $table->string('role')->default('user');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('phone');
            $table->dropColumn('birth_date');
            $table->dropColumn('gender');
            $table->dropColumn('profile_image');
            $table->dropColumn('address');
            $table->dropColumn('is_active');  
            $table->dropColumn('role'); 
        });
    }
};
