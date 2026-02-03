<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_assets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('asset_type', [
                'real_estate',
                'bank_account',
                'investment',
                'retirement',
                'business',
                'vehicle',
                'personal_property',
                'life_insurance',
                'other'
            ]);
            $table->string('description');
            $table->decimal('estimated_value', 15, 2)->nullable();
            $table->enum('ownership', ['individual', 'joint', 'trust', 'other'])->default('individual');
            $table->string('co_owner')->nullable();
            $table->string('account_number')->nullable();
            $table->string('institution')->nullable();
            $table->string('location')->nullable(); // For real estate
            $table->boolean('primary_residence')->default(false);
            $table->text('beneficiary_designation')->nullable();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('asset_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_assets');
    }
};
