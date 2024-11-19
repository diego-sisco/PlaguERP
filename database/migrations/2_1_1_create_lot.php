<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lot', function(Blueprint $table){
            $table->id();
            $table->foreignId('product_id')->constrained('product_catalog')->onDelete('cascade');
            $table->foreignId('warehouse_id')->constrained('warehouse')->onDelete('cascade');
            $table->string('registration_number');
            $table->date('expiration_date');
            $table->date('start_date');
            $table->date('end_date');
            $table->integer('amount');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lot');
    }
};
