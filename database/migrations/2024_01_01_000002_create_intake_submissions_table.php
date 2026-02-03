<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_submissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Section completion tracking
            $table->boolean('personal_info_complete')->default(false);
            $table->boolean('spouse_info_complete')->default(false);
            $table->boolean('children_complete')->default(false);
            $table->boolean('assets_complete')->default(false);
            $table->boolean('liabilities_complete')->default(false);
            $table->boolean('beneficiaries_complete')->default(false);
            $table->boolean('fiduciaries_complete')->default(false);
            $table->boolean('specific_gifts_complete')->default(false);
            $table->boolean('healthcare_complete')->default(false);
            $table->boolean('distribution_complete')->default(false);
            
            // Progress tracking
            $table->integer('current_section')->default(0);
            $table->integer('progress_percentage')->default(0);
            
            // Completion status
            $table->boolean('is_completed')->default(false);
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_submissions');
    }
};
