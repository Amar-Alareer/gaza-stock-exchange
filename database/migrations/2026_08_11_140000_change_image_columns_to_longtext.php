<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE categories MODIFY image LONGTEXT NULL");
            DB::statement("ALTER TABLE items MODIFY image_url LONGTEXT NULL");
        }
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE categories MODIFY image VARCHAR(255) NULL");
            DB::statement("ALTER TABLE items MODIFY image_url VARCHAR(255) NULL");
        }
    }
};
