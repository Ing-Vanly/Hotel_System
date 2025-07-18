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
            $table->dropColumn(['role_name', 'department', 'avatar', 'position']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
          Schema::table('users', function (Blueprint $table) {
            $table->string('role_name')->nullable();
            $table->string('department')->nullable();
            $table->string('avatar')->nullable();
            $table->string('position')->nullable();
        });
    }
};
