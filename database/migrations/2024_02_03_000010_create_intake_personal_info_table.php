<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_personal_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Personal Details
            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('preferred_name')->nullable();
            $table->date('date_of_birth');
            $table->string('ssn')->nullable();
            $table->enum('marital_status', ['single', 'married', 'divorced', 'widowed', 'domestic_partnership']);
            
            // Contact Information
            $table->string('street_address');
            $table->string('city');
            $table->string('county');
            $table->string('state')->default('Michigan');
            $table->string('zip_code');
            $table->text('mailing_address')->nullable();
            $table->string('primary_phone');
            $table->string('secondary_phone')->nullable();
            $table->string('email');
            $table->string('occupation')->nullable();
            
            // Citizenship
            $table->boolean('us_citizen')->default(true);
            $table->string('citizenship_country')->nullable();
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_personal_info');
    }
};
