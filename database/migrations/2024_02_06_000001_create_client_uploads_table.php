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
        Schema::create('client_uploads', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('filename');           // Stored filename (hashed)
            $table->string('original_name');      // Original filename from user
            $table->string('mime_type');          // e.g., application/pdf
            $table->enum('category', [
                'id_documents',
                'property_documents',
                'financial_documents',
                'beneficiary_information',
                'health_care_directives',
                'other'
            ]);
            $table->unsignedBigInteger('file_size'); // In bytes
            $table->timestamps();
            $table->softDeletes();                // Soft delete support
            
            $table->index(['user_id', 'category']);
            $table->index('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('client_uploads');
    }
};
