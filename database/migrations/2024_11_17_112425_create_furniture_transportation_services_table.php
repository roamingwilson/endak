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
        Schema::create('furniture_transportation_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('from_city');
            $table->string('from_neighborhood');
            $table->string('from_home');
            $table->string('to_city');
            $table->string('to_neighborhood');
            $table->string('to_home');
            $table->longText('notes')->nullable();
            $table->longText('notes_voice')->nullable();
            $table->enum('status',['open' , 'close' , 'pending' , 'confirm']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('furniture_transportation_services');
    }
};
