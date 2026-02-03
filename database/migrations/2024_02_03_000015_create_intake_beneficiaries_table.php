<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_beneficiaries', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('beneficiary_type', ['primary', 'contingent']);
            $table->string('full_name');
            $table->string('relationship');
            $table->string('address')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->date('date_of_birth')->nullable();
            $table->decimal('share_percentage', 5, 2)->nullable(); // e.g., 50.00 for 50%
            $table->text('conditions')->nullable();
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            $table->index('user_id');
            $table->index('beneficiary_type');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_beneficiaries');
    }
};
