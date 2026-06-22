<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Price extends Model
{
    protected $fillable = [
        'price',
        'source',
        'store_id',
        'item_id'


    ];

    public function stores(){
        return $this->belongsTo(Store::class,'store_id');
    }

    public function items(){
        return $this->belongsTo(Item::class);
    }
}
