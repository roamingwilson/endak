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
        Schema::create('heavy_equipment_services', function (Blueprint $table) {
            $table->id();
            $table->integer('heavy_equip_id')->default(0)->nullable();
            $table->string('location')->nullable();
            $table->string('equip_type')->nullable();
            $table->time('time')->nullable();
            $table->enum('status',['open' , 'close' , 'pending' , 'confirm']);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('heavy_equipment_services');
    }
};
