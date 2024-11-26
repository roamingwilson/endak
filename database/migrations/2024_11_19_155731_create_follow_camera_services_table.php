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
        Schema::create('follow_camera_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->tinyInteger('finger')->default(0);
            $table->tinyInteger('camera')->default(0);
            $table->tinyInteger('smart')->default(0);
            $table->tinyInteger('fire_system')->default(0);
            $table->tinyInteger('security_system')->default(0);
            $table->tinyInteger('network')->default(0);
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
        Schema::dropIfExists('follow_camera_services');
    }
};
