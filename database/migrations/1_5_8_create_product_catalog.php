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
        Schema::create('product_catalog', function (Blueprint $table) {
            /* DATOS BASICOS */
            $table->id();

            $table->foreignId('economic_id')->nullable()->constrained('economic_data_product')->onDelete('cascade'); // Datos económicos
            $table->foreignId('biocide_id')->nullable()->constrained('biocide')->onDelete('cascade'); // Relación entre grupo y tipos
            $table->foreignId('purpose_id')->nullable()->constrained('purpose')->onDelete('cascade'); // Finalidad
            $table->foreignId('linebusiness_id')->nullable()->constrained('line_business')->onDelete('cascade'); // Línea de negocio - despleg
            $table->foreignId('presentation_id')->nullable()->constrained('presentation')->onDelete('cascade'); // Presentación
            $table->foreignId('toxicity_categ_id')->nullable()->constrained('toxicity_categories')->onDelete('cascade'); // Categorías de toxicidad
            
            $table->foreignId('metric_id')->constrained('metric')->onDelete('cascade'); // Finalidad
            $table->foreignId('files_id')->constrained('product_files')->onDelete('cascade'); // Finalidad

            $table->string('image_path')->nullable(); /*foto del producto */
            $table->string('name'); /*nombre producto*/
            $table->string('business_name')->nullable(); /*nombre de la empresa*/
            $table->bigInteger('bar_code')->nullable();; /* codigo de barras */
            $table->longText('description')->nullable();; /* descripcion */
            $table->longText('execution_indications')->nullable(); /* indicaciones para ejeccucion */
            $table->string('manufacturer')->nullable(); /* fabricante */

            /* DATOS DE CLASIFICACION */
            $table->string('register_number')->nullable(); /* numero de registro */
            $table->date('validity_date')->nullable(); /* fecha de valido - caducidad */
            $table->string('active_ingredient')->nullable(); /* material activo */
            $table->decimal('per_active_ingredient', 10, 4)->nullable(); /* porcentaje de material activo */
            $table->string('dosage')->nullable(); /* dosificacion */
            $table->string('safety_period', 100)->nullable(); /* plazo de seguridad */
            $table->string('residual_effect')->nullable(); /* efecto residual */
            $table->boolean('is_obsolete')->default(false); /* obsoleto */
            $table->boolean('is_toxic')->default(false); /* toxicidad  -si-no*/
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('product_catalog');
    }
};
