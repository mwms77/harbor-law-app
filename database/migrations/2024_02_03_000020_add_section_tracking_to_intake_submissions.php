<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('intake_submissions', function (Blueprint $table) {
            // Section completion tracking
            $table->boolean('personal_info_complete')->default(false)->after('form_data');
            $table->boolean('spouse_info_complete')->default(false)->after('personal_info_complete');
            $table->boolean('children_complete')->default(false)->after('spouse_info_complete');
            $table->boolean('assets_complete')->default(false)->after('children_complete');
            $table->boolean('liabilities_complete')->default(false)->after('assets_complete');
            $table->boolean('beneficiaries_complete')->default(false)->after('liabilities_complete');
            $table->boolean('fiduciaries_complete')->default(false)->after('beneficiaries_complete');
            $table->boolean('specific_gifts_complete')->default(false)->after('fiduciaries_complete');
            $table->boolean('healthcare_complete')->default(false)->after('specific_gifts_complete');
            $table->boolean('distribution_complete')->default(false)->after('healthcare_complete');
            
            // Add submitted_at if it doesn't exist
            $table->timestamp('submitted_at')->nullable()->after('completed_at');
        });
    }

    public function down(): void
    {
        Schema::table('intake_submissions', function (Blueprint $table) {
            $table->dropColumn([
                'personal_info_complete',
                'spouse_info_complete',
                'children_complete',
                'assets_complete',
                'liabilities_complete',
                'beneficiaries_complete',
                'fiduciaries_complete',
                'specific_gifts_complete',
                'healthcare_complete',
                'distribution_complete',
                'submitted_at',
            ]);
        });
    }
};
