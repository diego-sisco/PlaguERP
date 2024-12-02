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
        Schema::create('warehouse_movement_product', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_movement_id')->constrained('warehouse_movements')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('product_catalog')->onDelete('cascade');
            $table->foreignId('lot_id')->nullable()->constrained('lot')->onDelete('cascade');
            $table->integer('amount'); // cantidad
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_movement_product');
    }
};
