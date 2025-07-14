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
        Schema::table('bookings', function (Blueprint $table) {
            // Add proper hotel booking fields
            $table->unsignedBigInteger('customer_id')->nullable()->after('bkg_id');
            $table->unsignedBigInteger('room_id')->nullable()->after('customer_id');
            $table->unsignedBigInteger('room_type_id')->nullable()->after('room_id');
            
            // Guest information (who is actually staying)
            $table->string('guest_name')->after('room_type_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
            $table->integer('guest_count')->default(1)->after('guest_phone');
            $table->json('guest_details')->nullable()->after('guest_count'); // Additional guests info
            
            // Proper date fields
            $table->date('check_in_date')->after('guest_details');
            $table->date('check_out_date')->after('check_in_date');
            $table->time('check_in_time')->nullable()->after('check_out_date');
            $table->time('check_out_time')->nullable()->after('check_in_time');
            
            // Financial tracking
            $table->decimal('room_rate', 10, 2)->default(0)->after('check_out_time');
            $table->decimal('total_amount', 10, 2)->default(0)->after('room_rate');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('total_amount');
            $table->decimal('balance_amount', 10, 2)->default(0)->after('paid_amount');
            $table->decimal('tax_amount', 10, 2)->default(0)->after('balance_amount');
            $table->decimal('discount_amount', 10, 2)->default(0)->after('tax_amount');
            
            // Status tracking
            $table->enum('booking_status', [
                'pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'no_show'
            ])->default('pending')->after('discount_amount');
            
            $table->enum('payment_status', [
                'pending', 'partial', 'paid', 'refunded', 'cancelled'
            ])->default('pending')->after('booking_status');
            
            // Hotel workflow fields
            $table->enum('booking_source', [
                'walk_in', 'phone', 'email', 'website', 'agency', 'corporate'
            ])->default('walk_in')->after('payment_status');
            
            $table->text('special_requests')->nullable()->after('booking_source');
            $table->json('services')->nullable()->after('special_requests'); // Extra services
            $table->string('confirmation_number')->nullable()->after('services');
            
            // Check-in/Check-out tracking
            $table->timestamp('actual_check_in')->nullable()->after('confirmation_number');
            $table->timestamp('actual_check_out')->nullable()->after('actual_check_in');
            $table->unsignedBigInteger('checked_in_by')->nullable()->after('actual_check_in');
            $table->unsignedBigInteger('checked_out_by')->nullable()->after('actual_check_out');
            
            // Additional tracking
            $table->text('notes')->nullable()->after('checked_out_by');
            $table->boolean('is_active')->default(true)->after('notes');
            
            // Foreign key constraints
            $table->foreign('customer_id')->references('id')->on('customers')->onDelete('set null');
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            $table->foreign('checked_in_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('checked_out_by')->references('id')->on('users')->onDelete('set null');
        });
        
        // Update existing data to use proper date format
        DB::statement("UPDATE bookings SET check_in_date = STR_TO_DATE(arrival_date, '%Y-%m-%d') WHERE arrival_date IS NOT NULL AND arrival_date != ''");
        DB::statement("UPDATE bookings SET check_out_date = STR_TO_DATE(depature_date, '%Y-%m-%d') WHERE depature_date IS NOT NULL AND depature_date != ''");
        DB::statement("UPDATE bookings SET guest_name = name WHERE name IS NOT NULL");
        DB::statement("UPDATE bookings SET guest_count = CAST(total_numbers AS UNSIGNED) WHERE total_numbers IS NOT NULL AND total_numbers REGEXP '^[0-9]+$'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['room_id']);
            $table->dropForeign(['checked_in_by']);
            $table->dropForeign(['checked_out_by']);
            
            $table->dropColumn([
                'customer_id', 'room_id', 'room_type_id', 'guest_name', 'guest_email', 
                'guest_phone', 'guest_count', 'guest_details', 'check_in_date', 
                'check_out_date', 'check_in_time', 'check_out_time', 'room_rate', 
                'total_amount', 'paid_amount', 'balance_amount', 'tax_amount', 
                'discount_amount', 'booking_status', 'payment_status', 'booking_source',
                'special_requests', 'services', 'confirmation_number', 'actual_check_in',
                'actual_check_out', 'checked_in_by', 'checked_out_by', 'notes', 'is_active'
            ]);
        });
    }
};