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
        Schema::create('warehouse_movements', function (Blueprint $table) {
            $table->id(); // id_movimiento as primary key
            $table->foreignId('warehouse_id')->constrained('warehouse')->onDelete('cascade');
            $table->foreignId('destination_warehouse_id')->nullable()->constrained('warehouse')->onDelete('cascade');
            $table->foreignId('movement_id')->constrained('movement_type')->onDelete('cascade');
            $table->foreignId('lot_id')->constrained('lot')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('product_catalog')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('user')->onDelete('cascade');
            $table->date('date'); // fecha
            $table->time('time'); // hora
            $table->text('observations')->nullable();
            $table->integer('amount');            
            $table->timestamps();
        });
        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse_movements');
    }
};
