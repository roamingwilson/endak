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
        Schema::create('spare_part_services', function (Blueprint $table) {
            $table->id();
            $table->integer('spare_part_id')->default(0)->nullable();
            $table->string('brand');
            $table->string('year_made');
            $table->string('part_number');
            $table->string('name');
            $table->string('from_city');
            $table->string('from_neighborhood');
            $table->string('to_city');
            $table->string('to_neighborhood');
            $table->text('notes')->nullable();
            $table->enum('status',['open' , 'close' , 'pending' , 'confirm']);
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('spare_part_services');
    }
};
