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
        Schema::create('pharmaceutical_products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->string('form'); // e.g., tablet, syrup, injection
            $table->string('dosage'); // e.g., 500mg, 10ml
            $table->decimal('price', 10, 2);
            $table->integer('stock_quantity')->default(0);
            $table->date('expiration_date');
            $table->string('manufacturer');
            $table->string('batch_number');
            $table->text('description')->nullable();
            $table->boolean('requires_prescription')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->softDeletes();

            // Indexes
            $table->index('category_id');
            $table->index('name');
            $table->index('batch_number');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pharmaceutical_products');
    }
};
