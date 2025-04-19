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
        Schema::create('van_truck_services', function (Blueprint $table) {
            $table->id();
            $table->integer('vantruck_id')->default(0)->nullable();
            $table->string('from_city')->nullable();
            $table->string('from_neighborhood')->nullable();
            $table->string('to_city')->nullable();
            $table->string('to_neighborhood')->nullable();
            $table->string('location')->nullable();
            $table->enum('status',['open' , 'close' , 'pending' , 'confirm']);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->longText('notes')->nullable();
            $table->time('time')->nullable();
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('van_truck_services');
    }
};
