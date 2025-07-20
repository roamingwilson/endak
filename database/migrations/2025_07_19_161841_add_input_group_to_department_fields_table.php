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
        Schema::table('department_fields', function (Blueprint $table) {
            $table->string('input_group')->nullable();
            $table->boolean('is_repeatable')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('department_fields', function (Blueprint $table) {
            $table->dropColumn('input_group');
        });
    }
};
