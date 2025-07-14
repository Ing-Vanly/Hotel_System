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
        Schema::table('rooms', function (Blueprint $table) {
            // Add missing columns
            $table->string('room_number')->nullable()->after('name');
            $table->foreignId('room_type_id')->nullable()->constrained('room_types')->after('room_number');
            $table->enum('status', ['available', 'occupied', 'maintenance', 'dirty', 'out_of_order'])
                  ->default('available')->after('message');
            $table->integer('floor_number')->nullable()->after('status');
            $table->integer('max_occupancy')->default(2)->after('floor_number');
            $table->boolean('is_active')->default(true)->after('max_occupancy');
            
            // Modify existing columns
            $table->decimal('rent', 10, 2)->change();
            $table->decimal('charges_for_cancellation', 10, 2)->change();
            $table->integer('bed_count')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('rooms', function (Blueprint $table) {
            $table->dropForeign(['room_type_id']);
            $table->dropColumn([
                'room_number',
                'room_type_id', 
                'status',
                'floor_number',
                'max_occupancy',
                'is_active'
            ]);
        });
    }
};