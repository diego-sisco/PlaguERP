<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

use function Laravel\Prompts\table;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('warehouse', function (Blueprint $table) {
            $table->id();
            $table->foreignId('branch_id')->constrained('branch')->onDelete('cascade');
            $table->string('name');
            $table->boolean('receive_material')->default(true);
            $table->boolean('active')->default(true);
            $table->string('address')->nullable(); // Dirección
            $table->integer('zip_code')->nullable(); // Código postal
            $table->string('city')->nullable(); // Municipio
            $table->string('state')->nullable(); // Estado/entidad
            $table->string('phone')->nullable(); // Teléfono
            $table->text('observations')->nullable(); 
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('warehouse');
    }
};
