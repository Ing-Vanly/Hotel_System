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
            // Add new columns for proper booking management
            $table->foreignId('customer_id')->nullable()->constrained()->after('bkg_id');
            $table->foreignId('room_id')->nullable()->constrained()->after('customer_id');
            $table->foreignId('room_type_id')->nullable()->constrained('room_types')->after('room_id');
            
            $table->string('guest_name')->nullable()->after('room_type_id');
            $table->string('guest_email')->nullable()->after('guest_name');
            $table->string('guest_phone')->nullable()->after('guest_email');
            $table->integer('guest_count')->default(1)->after('guest_phone');
            
            $table->date('check_in_date')->nullable()->after('guest_count');
            $table->date('check_out_date')->nullable()->after('check_in_date');
            
            $table->decimal('total_amount', 10, 2)->default(0)->after('check_out_date');
            $table->decimal('paid_amount', 10, 2)->default(0)->after('total_amount');
            
            $table->enum('payment_status', ['pending', 'partial', 'paid', 'refunded'])
                  ->default('pending')->after('paid_amount');
            $table->enum('booking_status', ['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled', 'no_show'])
                  ->default('pending')->after('payment_status');
                  
            $table->text('special_requests')->nullable()->after('booking_status');
            $table->string('booking_source')->default('admin')->after('special_requests');
            $table->text('notes')->nullable()->after('booking_source');
            
            // Modify existing date columns
            $table->date('arrival_date')->nullable()->change();
            $table->date('depature_date')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->dropForeign(['customer_id']);
            $table->dropForeign(['room_id']);
            $table->dropForeign(['room_type_id']);
            
            $table->dropColumn([
                'customer_id',
                'room_id',
                'room_type_id',
                'guest_name',
                'guest_email',
                'guest_phone',
                'guest_count',
                'check_in_date',
                'check_out_date',
                'total_amount',
                'paid_amount',
                'payment_status',
                'booking_status',
                'special_requests',
                'booking_source',
                'notes'
            ]);
        });
    }
};