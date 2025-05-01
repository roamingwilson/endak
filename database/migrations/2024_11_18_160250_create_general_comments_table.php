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
        Schema::create('general_comments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('service_provider')->constrained('users')->cascadeOnDelete();
            $table->foreignId('customer_id')->nullable()->constrained('users')->nullOnDelete();
            $table->text('body')->nullable();
            $table->float('price')->nullable();
            $table->date('date')->nullable();
            $table->time('time')->nullable();
            $table->longText('notes')->nullable();
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();
            $table->string('location')->nullable();
            $table->string('day')->nullable();
            $table->string('number_of_days_of_warranty')->nullable();
            $table->unsignedBigInteger('commentable_id');
            $table->string('commentable_type');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('general_comments');
    }
};
