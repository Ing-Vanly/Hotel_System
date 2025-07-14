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
        // Room status logs - track all room status changes
        Schema::create('room_status_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->string('old_status');
            $table->string('new_status');
            $table->unsignedBigInteger('changed_by');
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('changed_by')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Room cleaning logs - track cleaning activities
        Schema::create('room_cleaning_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->unsignedBigInteger('cleaned_by');
            $table->timestamp('cleaned_at');
            $table->text('notes')->nullable();
            $table->integer('duration_minutes')->nullable();
            $table->timestamps();
            
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('cleaned_by')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Maintenance requests
        Schema::create('maintenance_requests', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->enum('issue_type', ['plumbing', 'electrical', 'ac', 'furniture', 'cleaning', 'other']);
            $table->enum('priority', ['low', 'medium', 'high', 'urgent']);
            $table->text('description');
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->unsignedBigInteger('requested_by');
            $table->unsignedBigInteger('assigned_to')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('started_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->text('completion_notes')->nullable();
            $table->decimal('cost', 10, 2)->nullable();
            $table->timestamps();
            
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('assigned_to')->references('id')->on('users')->onDelete('set null');
        });
        
        // Lost and found items
        Schema::create('lost_found_items', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('room_id');
            $table->text('item_description');
            $table->date('found_date');
            $table->string('location_details')->nullable();
            $table->enum('status', ['found', 'claimed', 'disposed'])->default('found');
            $table->unsignedBigInteger('found_by');
            $table->string('claimed_by_name')->nullable();
            $table->string('claimed_by_contact')->nullable();
            $table->timestamp('claimed_at')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('cascade');
            $table->foreign('found_by')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Guest services - track additional services provided
        Schema::create('guest_services', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('service_type'); // laundry, room_service, spa, etc.
            $table->string('service_name');
            $table->text('description')->nullable();
            $table->decimal('amount', 10, 2)->default(0);
            $table->enum('status', ['pending', 'in_progress', 'completed', 'cancelled'])->default('pending');
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('completed_at')->nullable();
            $table->unsignedBigInteger('requested_by')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
            
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('requested_by')->references('id')->on('users')->onDelete('set null');
        });
        
        // Payment transactions
        Schema::create('payment_transactions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('booking_id');
            $table->string('transaction_id')->unique();
            $table->enum('payment_method', ['cash', 'card', 'upi', 'bank_transfer', 'cheque']);
            $table->decimal('amount', 10, 2);
            $table->enum('transaction_type', ['payment', 'refund']);
            $table->enum('status', ['pending', 'completed', 'failed', 'cancelled'])->default('pending');
            $table->string('reference_number')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('processed_by');
            $table->timestamp('processed_at')->useCurrent();
            $table->timestamps();
            
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
            $table->foreign('processed_by')->references('id')->on('users')->onDelete('cascade');
        });
        
        // Hotel configuration settings
        Schema::create('hotel_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value');
            $table->string('type')->default('string'); // string, integer, boolean, json
            $table->text('description')->nullable();
            $table->timestamps();
        });
        
        // Insert default hotel settings
        DB::table('hotel_settings')->insert([
            ['key' => 'hotel_name', 'value' => 'Hotel Management System', 'type' => 'string', 'description' => 'Hotel name'],
            ['key' => 'check_in_time', 'value' => '14:00', 'type' => 'string', 'description' => 'Default check-in time'],
            ['key' => 'check_out_time', 'value' => '11:00', 'type' => 'string', 'description' => 'Default check-out time'],
            ['key' => 'tax_rate', 'value' => '18.00', 'type' => 'decimal', 'description' => 'Tax rate percentage'],
            ['key' => 'currency', 'value' => 'USD', 'type' => 'string', 'description' => 'Hotel currency'],
            ['key' => 'cancellation_hours', 'value' => '24', 'type' => 'integer', 'description' => 'Free cancellation hours before check-in'],
            ['key' => 'late_checkout_fee', 'value' => '50.00', 'type' => 'decimal', 'description' => 'Late checkout fee'],
            ['key' => 'early_checkin_fee', 'value' => '30.00', 'type' => 'decimal', 'description' => 'Early check-in fee'],
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payment_transactions');
        Schema::dropIfExists('guest_services');
        Schema::dropIfExists('lost_found_items');
        Schema::dropIfExists('maintenance_requests');
        Schema::dropIfExists('room_cleaning_logs');
        Schema::dropIfExists('room_status_logs');
        Schema::dropIfExists('hotel_settings');
    }
};