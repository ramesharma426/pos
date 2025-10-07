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
            //$table->string('image_url');
            $table->string('name', 50);
            $table->foreignId('unit_id');
            $table->foreignId('category_id');
            $table->integer('stock', false, true);
            $table->decimal('per_unit_price');
            //$table->decimal('rate',10,2, true);
            //$table->string('product_code');
            $table->softDeletes();
            $table->timestamps();
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
