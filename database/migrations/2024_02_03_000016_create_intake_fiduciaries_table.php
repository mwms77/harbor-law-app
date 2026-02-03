<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_fiduciaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('role_type', [
                'personal_representative',
                'successor_personal_representative',
                'trustee',
                'successor_trustee',
                'guardian',
                'successor_guardian',
                'healthcare_poa',
                'financial_poa'
            ]);
            $table->string('full_name');
            $table->string('relationship');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->boolean('professional')->default(false); // e.g., bank as trustee
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('role_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_fiduciaries');
    }
};
