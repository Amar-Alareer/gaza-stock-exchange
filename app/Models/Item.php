<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category_id',
    ];

    public function prices()
    {
        return $this->hasMany(Price::class);
    }

    /**
     * أرخص سعر مسجل لهيك الصنف (مع اسم المحل) - تُستخدم بالصفحة الرئيسية وصفحة الأسعار
     */
    public function cheapestPrice()
    {
        return $this->hasOne(Price::class)->ofMany('price', 'min')->with('store:id,name');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
