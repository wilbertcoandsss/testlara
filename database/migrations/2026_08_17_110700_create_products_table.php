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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // NAME VARCHAR(225) NOT NULL
            $table->string('slug'); // Slug -> desc singkat
            $table->string('description');
            $table->integer('price');
            $table->integer('stock');
            // Foregin Key
            $table->foreignId('product_type_id')->constrained('product_types')->cascadeOnDelete();
  
            $table->timestamps(); // created_at & updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
