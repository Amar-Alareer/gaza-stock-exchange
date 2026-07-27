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
        // المحلات
        Schema::create('stores', function (Blueprint $table) {
            $table->id();
            $table->foreignId('region_id')->constrained('regions')->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();      // نص عشان الصفر
            $table->string('governorate')->nullable(); // المحافظة
            $table->string('sub_area')->nullable();   // المنطقة الفرعية / الحي
            $table->string('address')->nullable();    // العنوان التفصيلي
            $table->string('working_hours')->nullable(); // ساعات العمل
            $table->decimal('latitude', 10, 8)->nullable(); // عشري
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('facebook_url')->nullable();
            $table->string('instagram_url')->nullable();
            $table->string('telegram_url')->nullable();
            $table->string('image')->nullable();       // صورة المتجر
            $table->string('cover_image')->nullable(); // صورة الغلاف
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stores');
    }
};
