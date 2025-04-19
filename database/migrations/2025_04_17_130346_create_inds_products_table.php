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
        Schema::create('inds_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ind_sub_category_id')->constrained()->onDelete('cascade');
            $table->foreignId('inds_category_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('image');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inds_products');
    }
};
