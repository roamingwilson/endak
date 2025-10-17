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
        Schema::create('category_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->onDelete('cascade');
            $table->string('name'); // Field key (e.g., from_city)
            $table->string('name_ar'); // Arabic name
            $table->string('name_en'); // English name
            $table->enum('type', [
                'title', 'text', 'number', 'select', 'checkbox',
                'textarea', 'image', 'date', 'time'
            ]);
            $table->text('value')->nullable(); // Default value
            $table->json('options')->nullable(); // For select fields
            $table->string('input_group')->nullable(); // For grouping fields
            $table->boolean('is_required')->default(false);
            $table->boolean('is_repeatable')->default(false);
            $table->text('description')->nullable();
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->unique(['category_id', 'name']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('category_fields');
    }
};
