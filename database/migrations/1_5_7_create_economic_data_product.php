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
        Schema::create('economic_data_product', function (Blueprint $table) {
            $table->id();
            $table->float('purchase_price')->nullable();
            $table->integer('min_purchase_unit')->nullable();
            $table->integer('mult_purchase')->nullable();
            $table->foreignId('supplier_id')->nullable()->constrained('supplier')->onDelete('cascade');
            $table->boolean('selling')->nullable();//true o false
            $table->float('selling_price')->nullable();
            $table->integer('subaccount_purchases')->nullable();
            $table->integer('subaccount_sales')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('economic_data_product');
    }
};
