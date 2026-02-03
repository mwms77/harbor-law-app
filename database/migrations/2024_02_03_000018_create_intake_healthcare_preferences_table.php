<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_healthcare_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Life-sustaining treatment preferences
            $table->enum('life_sustaining_treatment', [
                'maintain',
                'discontinue',
                'comfort_care',
                'undecided'
            ])->nullable();
            
            // Organ donation
            $table->boolean('organ_donation')->nullable();
            $table->text('organ_donation_specifics')->nullable();
            
            // Anatomical gifts
            $table->boolean('anatomical_gift')->nullable();
            $table->text('anatomical_gift_details')->nullable();
            
            // Funeral preferences
            $table->enum('funeral_preference', ['burial', 'cremation', 'no_preference', 'other'])->nullable();
            $table->text('funeral_instructions')->nullable();
            $table->string('funeral_prepaid')->nullable();
            
            // Additional healthcare wishes
            $table->text('additional_healthcare_wishes')->nullable();
            
            // Religious/spiritual preferences
            $table->text('religious_preferences')->nullable();
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_healthcare_preferences');
    }
};
