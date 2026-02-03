<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_spouse_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('spouse_name');
            $table->date('spouse_dob')->nullable();
            $table->string('spouse_ssn')->nullable();
            $table->string('spouse_occupation')->nullable();
            $table->boolean('spouse_us_citizen')->default(true);
            $table->string('spouse_citizenship_country')->nullable();
            $table->date('marriage_date')->nullable();
            $table->string('marriage_location')->nullable();
            $table->boolean('prenuptial_agreement')->default(false);
            $table->boolean('previous_marriage')->default(false);
            $table->text('previous_marriage_details')->nullable();
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_spouse_info');
    }
};
