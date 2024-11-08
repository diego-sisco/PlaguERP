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
        Schema::create('lead', function (Blueprint $table) {
            $table->id();
            $table->integer('company_category_id')->constrained('company_category')->onDelete('cascade')->nullable();
            $table->integer('administrative_id')->nullable()->constrained('user')->onDelete('cascade');
            $table->integer('service_type_id')->constrained('service_type')->onDelete('cascade')->nullable();
            $table->integer('branch_id')->constrained('branch')->onDelete('cascade')->nullable();
            $table->integer('company_id')->nullable()->constrained('company')->onDelete('cascade');
            $table->string('name')->nullable();
            $table->string('address')->nullable();
            $table->integer('zip_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('status')->nullable();
            $table->string('city')->nullable(); 
            $table->string('state')->nullable();
            $table->string('map_location_url', 1000)->nullable();
            $table->string('reason', 1024)->nullable();
            $table->date('tracking_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead');
    }
};
