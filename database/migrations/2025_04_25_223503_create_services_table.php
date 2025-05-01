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
        Schema::create('services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // صاحب الطلب
            $table->foreignId('department_id')->constrained('departments')->cascadeOnDelete(); // صاحب الطلب
            $table->foreignId('provider_id')->nullable()->constrained('users')->cascadeOnDelete(); // مقدم الخدمة (اختياري)

            $table->string('type'); // نوع الخدمة مثلاً: maintenance, spare_part, transport...


            // الموقع
            $table->string('city')->nullable();
            $table->string('neighborhood')->nullable();

            $table->string('from_city')->nullable();
            $table->string('from_neighborhood')->nullable();

            $table->string('to_city')->nullable();
            $table->string('to_neighborhood')->nullable();


            // بيانات إضافية
            $table->string('model')->nullable();
            $table->string('year')->nullable();
            $table->string('brand')->nullable();
            $table->string('part_number')->nullable();
            $table->string('equip_type')->nullable();
            $table->string('car_type')->nullable();
            $table->string('location')->nullable();
            $table->enum('gender', ['male', 'female'])->nullable();

            // الوقت
            $table->time('time')->nullable();
            $table->date('date')->nullable();
            $table->string('day')->nullable();

            // خواص الخدمة
            $table->integer('quantity')->nullable();
            $table->float('price')->nullable();
            $table->string('drink_width')->default(0);
            $table->string('wall_width')->default(0);
            $table->tinyInteger('split')->nullable();
            $table->tinyInteger('window')->nullable();
            $table->tinyInteger('clean')->nullable();
            $table->tinyInteger('feryoun')->nullable();
            $table->tinyInteger('maintance')->nullable();
            $table->tinyInteger('finger')->nullable();
            $table->tinyInteger('camera')->nullable();
            $table->tinyInteger('smart')->nullable();
            $table->tinyInteger('fire_system')->nullable();
            $table->tinyInteger('security_system')->nullable();
            $table->tinyInteger('network')->nullable();
            $table->tinyInteger('installation')->nullable();
            $table->tinyInteger('disassembly')->nullable();
            $table->longText('notes')->nullable();
            $table->longText('notes_voice')->nullable();
            $table->enum('status', ['open', 'close', 'pending', 'confirm'])->default('open');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('services');
    }
};
