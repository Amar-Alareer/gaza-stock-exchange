<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // تعديل عمود image_url إلى LONGTEXT ليتسع لصور Base64 السريعة بأي حجم
        try {
            DB::statement("ALTER TABLE `items` MODIFY COLUMN `image_url` LONGTEXT NULL");
        } catch (\Throwable $e) {
            // fallback if using sqlite
            Schema::table('items', function ($table) {
                $table->longText('image_url')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        try {
            DB::statement("ALTER TABLE `items` MODIFY COLUMN `image_url` TEXT NULL");
        } catch (\Throwable $e) {
            // ignore
        }
    }
};
