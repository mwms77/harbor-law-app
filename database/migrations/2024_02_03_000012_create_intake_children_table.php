<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_children', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('full_name');
            $table->date('date_of_birth')->nullable();
            $table->enum('relationship', ['biological', 'adopted', 'step', 'other'])->default('biological');
            $table->boolean('minor')->default(false);
            $table->boolean('special_needs')->default(false);
            $table->text('special_needs_description')->nullable();
            $table->string('current_residence')->nullable();
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_children');
    }
};
