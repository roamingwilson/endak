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
        Schema::create('air_condition_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->tinyInteger('split')->default(0);
            $table->tinyInteger('window')->default(0);
            $table->tinyInteger('clean')->default(0);
            $table->tinyInteger('feryoun')->default(0);
            $table->tinyInteger('maintance')->default(0);
            $table->string('model');
            $table->tinyInteger('quantity')->default(1);
            $table->string('city');
            $table->string('neighborhood');
            $table->time('time')->nullable();
            $table->date('date')->nullable();
            $table->longText('notes')->nullable();
            $table->enum('status',['open' , 'close' , 'pending' , 'confirm']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('air_condition_services');
    }
};
