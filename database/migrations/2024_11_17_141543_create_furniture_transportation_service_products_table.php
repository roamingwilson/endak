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
        Schema::create('furniture_transportation_service_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained('furniture_transportation_products')->cascadeOnDelete();
            $table->foreignId('service_id')->constrained('furniture_transportation_services')->cascadeOnDelete();
            $table->tinyInteger('quantity')->default(1);
            $table->tinyInteger('installation')->default(1);
            $table->tinyInteger('disassembly')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('furniture_transportation_service_products');
    }
};
