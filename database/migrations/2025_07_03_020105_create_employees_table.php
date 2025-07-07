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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            // Basic Info
            $table->string('first_name');
            $table->string('last_name');
            $table->enum('gender', ['male', 'female', 'other']);
            $table->date('dob');
            // Contact Info
            $table->string('phone');
            $table->string('email')->unique();
            $table->string('national_id')->unique();
            $table->text('address');
            $table->string('photo')->nullable();
            // Job Info
            $table->string('position');
            $table->string('department');
            $table->date('joining_date');
            $table->decimal('salary', 10, 2);
            $table->enum('status', ['active', 'inactive', 'terminated'])->default('active');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
