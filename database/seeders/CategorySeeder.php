<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Item;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $defaultCategories = [
            [
                'name' => 'خضراوات',
                'image' => 'https://images.unsplash.com/photo-1540420773420-3366772f4999?w=300&auto=format&fit=crop&q=80',
                'description' => 'الخضراوات الطازجة اليومية من المزارع'
            ],
            [
                'name' => 'فواكه',
                'image' => 'https://images.unsplash.com/photo-1619566636858-adf3ef46400b?w=300&auto=format&fit=crop&q=80',
                'description' => 'فواكه طازجة ومغذية متوفرة في الأسواق'
            ],
            [
                'name' => 'لحوم ودواجن',
                'image' => 'https://images.unsplash.com/photo-1603048588665-791ca8aea617?w=300&auto=format&fit=crop&q=80',
                'description' => 'لحوم حمراء ودواجن طازجة ومجمدة'
            ],
            [
                'name' => 'مواد تموينية',
                'image' => 'https://images.unsplash.com/photo-1586201375761-83865001e31c?w=300&auto=format&fit=crop&q=80',
                'description' => 'أرز، سكر، طحين، ومستلزمات التموين الأساسية'
            ],
            [
                'name' => 'زيوت بدهون',
                'image' => 'https://images.unsplash.com/photo-1474979266404-7eaacbcd87c5?w=300&auto=format&fit=crop&q=80',
                'description' => 'زيوت طعام، زيت زيتون، وسمنة'
            ],
            [
                'name' => 'ألبان وأجبان',
                'image' => 'https://images.unsplash.com/photo-1628088062854-d1870b4553da?w=300&auto=format&fit=crop&q=80',
                'description' => 'حليب، أجبان، لبنة، وزبدة طازجة'
            ],
            [
                'name' => 'مشروبات وحلويات',
                'image' => 'https://images.unsplash.com/photo-1551024709-8f23befc6f87?w=300&auto=format&fit=crop&q=80',
                'description' => 'عصائر، مياه، وشوكولاتة وحلويات'
            ],
            [
                'name' => 'أخرى',
                'image' => null,
                'description' => 'تصنيفات ومنتجات متنوعة أخرى'
            ],
        ];

        foreach ($defaultCategories as $catData) {
            $slug = Str::slug($catData['name']) ?: 'cat-' . rand(100, 999);
            
            $category = Category::where('name', $catData['name'])->first();
            if (!$category) {
                $baseSlug = $slug;
                $counter = 1;
                while (Category::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $counter;
                    $counter++;
                }
                $category = Category::create([
                    'name' => $catData['name'],
                    'slug' => $slug,
                    'description' => $catData['description'],
                    'image' => $catData['image'],
                    'is_active' => true,
                ]);
            }
        }

        // Link any orphan items to matching category
        $items = Item::all();
        foreach ($items as $item) {
            if (!$item->category_id || $item->category) {
                $cat = Category::where('name', trim($item->category))->first();
                if (!$cat) {
                    $cat = Category::where('name', 'like', '%' . trim($item->category) . '%')->first();
                }
                if (!$cat) {
                    $cat = Category::first();
                }
                if ($cat) {
                    $item->category_id = $cat->id;
                    $item->category = $cat->name;
                    $item->save();
                }
            }
        }
    }
}
