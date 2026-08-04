<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1) نضيف عمود category_id مبدئيًا nullable لحتى ننقل البيانات القديمة بدون ما نفقدها
        Schema::table('items', function (Blueprint $table) {
            $table->foreignId('category_id')
                ->nullable()
                ->after('id')
                ->constrained('categories')
                ->nullOnDelete();
        });

        // 2) ننقل القيم النصية القديمة (category) إلى جدول categories ونربطها
        if (Schema::hasColumn('items', 'category')) {
            $items = DB::table('items')->select('id', 'category')->get();

            foreach ($items as $item) {
                $name = trim((string) $item->category);

                if ($name === '') {
                    continue;
                }

                $categoryId = DB::table('categories')->where('name', $name)->value('id');

                if (! $categoryId) {
                    $categoryId = DB::table('categories')->insertGetId([
                        'name' => $name,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::table('items')->where('id', $item->id)->update(['category_id' => $categoryId]);
            }

            // 3) نحذف العمود النصي القديم بعد ما انتقلت بياناته بالكامل
            Schema::table('items', function (Blueprint $table) {
                $table->dropColumn('category');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('items', function (Blueprint $table) {
            $table->string('category')->nullable()->after('id');
        });

        // نعيد تعبئة العمود النصي من اسم الكاتيجوري المرتبط (إن وجد) قبل حذف العمود
        $items = DB::table('items')->select('id', 'category_id')->get();
        foreach ($items as $item) {
            if ($item->category_id) {
                $name = DB::table('categories')->where('id', $item->category_id)->value('name');
                DB::table('items')->where('id', $item->id)->update(['category' => $name]);
            }
        }

        Schema::table('items', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn('category_id');
        });
    }
};
