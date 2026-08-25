<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category'
    ];

    /**
     * العلاقة مع جدول الأسعار: المنتج الواحد له عدة أسعار في عدة متاجر
     */
    public function prices()
    {
        return $this->hasMany(Price::class);
    }
}