<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category',
        'image_url',
        'min_price',
        'category_id'
    ];

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
