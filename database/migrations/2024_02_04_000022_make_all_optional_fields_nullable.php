<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Fix intake_personal_info - county should be optional
        Schema::table('intake_personal_info', function (Blueprint $table) {
            $table->string('county')->nullable()->change();
        });

        // Fix intake_spouse_info - spouse_name should be optional (only filled if married)
        Schema::table('intake_spouse_info', function (Blueprint $table) {
            $table->string('spouse_name')->nullable()->change();
        });

        // Fix intake_children - full_name should be optional (user might add empty then fill later)
        Schema::table('intake_children', function (Blueprint $table) {
            $table->string('full_name')->nullable()->change();
        });

        // Fix intake_assets - description should be optional
        Schema::table('intake_assets', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
        });

        // Fix intake_liabilities - these should all be optional
        Schema::table('intake_liabilities', function (Blueprint $table) {
            $table->string('description')->nullable()->change();
            $table->string('lender')->nullable()->change();
            $table->decimal('balance_owed', 15, 2)->nullable()->change();
        });

        // Fix intake_fiduciaries - relationship should be optional
        Schema::table('intake_fiduciaries', function (Blueprint $table) {
            $table->string('full_name')->nullable()->change();
            $table->string('relationship')->nullable()->change();
        });

        // Fix intake_beneficiaries - relationship should be optional
        Schema::table('intake_beneficiaries', function (Blueprint $table) {
            $table->string('full_name')->nullable()->change();
            $table->string('relationship')->nullable()->change();
        });

        // Fix intake_specific_gifts
        Schema::table('intake_specific_gifts', function (Blueprint $table) {
            $table->text('item_description')->nullable()->change();
            $table->string('recipient_name')->nullable()->change();
            $table->string('recipient_relationship')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('intake_personal_info', function (Blueprint $table) {
            $table->string('county')->nullable(false)->change();
        });

        Schema::table('intake_spouse_info', function (Blueprint $table) {
            $table->string('spouse_name')->nullable(false)->change();
        });

        Schema::table('intake_children', function (Blueprint $table) {
            $table->string('full_name')->nullable(false)->change();
        });

        Schema::table('intake_assets', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change();
        });

        Schema::table('intake_liabilities', function (Blueprint $table) {
            $table->string('description')->nullable(false)->change();
            $table->string('lender')->nullable(false)->change();
            $table->decimal('balance_owed', 15, 2)->nullable(false)->change();
        });

        Schema::table('intake_fiduciaries', function (Blueprint $table) {
            $table->string('full_name')->nullable(false)->change();
            $table->string('relationship')->nullable(false)->change();
        });

        Schema::table('intake_beneficiaries', function (Blueprint $table) {
            $table->string('full_name')->nullable(false)->change();
            $table->string('relationship')->nullable(false)->change();
        });

        Schema::table('intake_specific_gifts', function (Blueprint $table) {
            $table->text('item_description')->nullable(false)->change();
            $table->string('recipient_name')->nullable(false)->change();
            $table->string('recipient_relationship')->nullable(false)->change();
        });
    }
};
