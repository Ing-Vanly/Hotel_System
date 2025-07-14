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
        Schema::table('room_types', function (Blueprint $table) {
            // Add new columns to room_types table
            $table->text('description')->nullable()->after('room_name');
            $table->decimal('base_price', 10, 2)->default(0)->after('description');
            $table->integer('max_occupancy')->default(2)->after('base_price');
            $table->json('amenities')->nullable()->after('max_occupancy');
            $table->boolean('is_active')->default(true)->after('amenities');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('room_types', function (Blueprint $table) {
            $table->dropColumn([
                'description',
                'base_price',
                'max_occupancy',
                'amenities',
                'is_active'
            ]);
        });
    }
};