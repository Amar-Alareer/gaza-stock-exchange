<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'name',
        'category'
    ];

    
    public function prices()
    {
        return $this->hasMany(Price::class);
    }
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
