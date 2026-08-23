<?php

namespace Database\Factories;

use App\Models\Item;
use Illuminate\Database\Eloquent\Factories\Factory;

class ItemFactory extends Factory
{
    protected $model = Item::class;

    public function definition(): array
    {
        $stores = ['مول لاكاسا', 'محلات عمو عماد', 'مول التاج', 'مذبح التاج', 'سوبرماركت المدينة'];
        $units = ['1 كيلو', '25 كيلو', '750 غرام', '1 لتر', 'حبة'];
        $products = ['طحين', 'بندورة', 'سكر', 'صابون الغسيل', 'دجاج', 'زيت زيتون', 'أرز'];

        return [
            'name' => fake()->randomElement($products),
            'price' => fake()->numberBetween(5, 100),
            'unit' => fake()->randomElement($units),
            'store_name' => fake()->randomElement($stores),
        ];
    }
}