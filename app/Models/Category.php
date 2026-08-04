<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name',
        'icon',
    ];

    /**
     * كل الأصناف (Items) التابعة لهاي الكاتيجوري
     */
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
