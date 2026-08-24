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
        // الاصناف
        Schema::create('items', function (Blueprint $table) {
<<<<<<< HEAD
    $table->id();
    $table->string('name');
    $table->string('category')->nullable(); // أو اجعله قابل للإلغاء
    $table->decimal('price', 8, 2);         // إضافة عمود السعر
    $table->string('unit');                 // إضافة عمود الوحدة
    $table->string('store_name');           // إضافة عمود اسم المتجر
    $table->timestamps();
});
=======
            $table->id();
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->string('name');
            $table->string('category');
            $table->longText('image_url')->nullable();
            $table->decimal('min_price', 10, 2)->nullable();
            $table->timestamps();
        });
>>>>>>> 80e6f1c0210fb9a930c900753ddfaae084a21443
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
