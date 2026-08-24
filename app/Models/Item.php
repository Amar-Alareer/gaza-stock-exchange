<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',      // إضافة السعر
        'unit',       // إضافة الوحدة
        'store_name', // إضافة اسم المتجر
        'category',
        'image_url',
        'min_price',
        'category_id'
    ];

    public function prices()
    {
        return $this->hasMany(Price::class, 'item_id');
    }


    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
