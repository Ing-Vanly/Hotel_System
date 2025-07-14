<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // First, update any existing 'cancelled' status to 'rejected'
        DB::table('leaves')
            ->where('status', 'cancelled')
            ->update(['status' => 'rejected']);

        // Now modify the enum to ensure it has the correct values
        DB::statement("ALTER TABLE leaves MODIFY COLUMN status ENUM('pending', 'approved', 'rejected') DEFAULT 'pending'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to the old enum (if needed)
        DB::statement("ALTER TABLE leaves MODIFY COLUMN status ENUM('pending', 'approved', 'cancelled') DEFAULT 'pending'");
        
        // Update any 'rejected' status back to 'cancelled'
        DB::table('leaves')
            ->where('status', 'rejected')
            ->update(['status' => 'cancelled']);
    }
};