<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('intake_liabilities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->enum('liability_type', [
                'mortgage',
                'home_equity',
                'auto_loan',
                'student_loan',
                'credit_card',
                'personal_loan',
                'business_debt',
                'other'
            ]);
            $table->string('description');
            $table->string('lender');
            $table->decimal('balance_owed', 15, 2);
            $table->decimal('monthly_payment', 10, 2)->nullable();
            $table->string('account_number')->nullable();
            $table->text('notes')->nullable();
            $table->integer('sort_order')->default(0);
            
            $table->timestamps();
            
            $table->index('user_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('intake_liabilities');
    }
};
