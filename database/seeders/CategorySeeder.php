<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            ['name' => 'مواد تموينية', 'icon' => '🌾'],
            ['name' => 'خضراوات',      'icon' => '🥬'],
            ['name' => 'فواكه',        'icon' => '🥭'],
            ['name' => 'لحوم ودواجن',  'icon' => '🍗'],
            ['name' => 'ألبان وأجبان', 'icon' => '🧀'],
            ['name' => 'مشروبات',      'icon' => '☕'],
            ['name' => 'مستلزمات نظافة', 'icon' => '🧴'],
            ['name' => 'وقود',         'icon' => '⛽'],
        ];

        foreach ($categories as $category) {
            Category::firstOrCreate(['name' => $category['name']], $category);
        }
    }
}
