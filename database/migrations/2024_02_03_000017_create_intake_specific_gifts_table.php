<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_specific_gifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->text('item_description');
            $table->string('recipient_name');
            $table->string('recipient_relationship');
            $table->text('conditions')->nullable();
            $table->enum('if_predeceased', ['lapse', 'descendants', 'alternate'])->default('lapse');
            $table->string('alternate_recipient')->nullable();
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_specific_gifts');
    }
};
