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
        // التحقق من وجود عمود sub_category_id
        if (!Schema::hasColumn('services', 'sub_category_id')) {
            Schema::table('services', function (Blueprint $table) {
                $table->foreignId('sub_category_id')->nullable()->after('category_id')->constrained('sub_categories')->onDelete('cascade');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('services', 'sub_category_id')) {
            Schema::table('services', function (Blueprint $table) {
                $table->dropForeign(['sub_category_id']);
                $table->dropColumn('sub_category_id');
            });
        }
    }
};
