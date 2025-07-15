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
        Schema::create('whatsapp_sender_department', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('whatsapp_sender_id');
            $table->unsignedBigInteger('department_id');
            $table->foreign('whatsapp_sender_id')->references('id')->on('whatsapp_senders')->onDelete('cascade');
            $table->foreign('department_id')->references('id')->on('departments')->onDelete('cascade');
            $table->unique(['whatsapp_sender_id', 'department_id'], 'sender_dept_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('whatsapp_sender_department');
    }
};
