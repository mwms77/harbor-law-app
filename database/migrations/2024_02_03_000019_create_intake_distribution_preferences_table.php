<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_distribution_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Distribution method
            $table->enum('distribution_method', [
                'outright',
                'trust',
                'conditional',
                'combination'
            ])->default('outright');
            
            // Age restrictions
            $table->boolean('age_restrictions')->default(false);
            $table->integer('distribution_age')->nullable(); // Age when beneficiaries receive full distribution
            $table->text('staged_distribution')->nullable(); // e.g., "1/3 at 25, 1/3 at 30, rest at 35"
            
            // Disinheritance
            $table->boolean('disinherit_anyone')->default(false);
            $table->text('disinheritance_details')->nullable();
            
            // Equal vs unequal distribution
            $table->boolean('equal_distribution')->default(true);
            $table->text('unequal_distribution_explanation')->nullable();
            
            // Residue distribution
            $table->text('residue_distribution')->nullable(); // What happens to everything else
            
            // Charitable bequests
            $table->boolean('charitable_bequests')->default(false);
            $table->text('charitable_bequest_details')->nullable();
            
            // Special instructions
            $table->text('special_instructions')->nullable();
            
            // Digital assets
            $table->text('digital_assets_instructions')->nullable();
            
            $table->timestamps();
            
            $table->unique('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_distribution_preferences');
    }
};
