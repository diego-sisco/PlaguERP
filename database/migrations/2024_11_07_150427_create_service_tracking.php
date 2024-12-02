<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('service_tracking', function (Blueprint $table) {
            $table->id();
            $table->morphs('model'); // Crea 'model_id' y 'model_type'
            $table->foreignId('service_id')->constrained('service')->onDelete('cascade');
            $table->date('tracking_date');
            $table->time('tracking_time')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('service_tracking');
    }
};
