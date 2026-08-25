<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $stores = ['مول لاكاسا', 'محلات عمو عماد', 'مول التاج', 'مذبح التاج', 'سوبرماركت المدينة'];
        $units = ['1 كيلو', '25 كيلو', '750 غرام', '1 لتر', 'حبة'];
        $products = ['طحين', 'بندورة', 'سكر', 'صابون الغسيل', 'دجاج', 'زيت زيتون', 'أرز'];

        $data = [];

        for ($i = 0; $i < 20; $i++) {
            $data[] = [
                'name'       => $products[array_rand($products)],
                'price'      => rand(5, 100),
                'unit'       => $units[array_rand($units)],
                'store_name' => $stores[array_rand($stores)],
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        // إدخال 20 صف دفعة واحدة في جدول items بدون حاجة لفاكتوري أو نت
        DB::table('items')->insert($data);
        $this->call([
            CategorySeeder::class,
            UserSeeder::class,
            StoreSeeder::class,
        ]);
    }
}