<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Price extends Model
{
    protected $fillable = [
        'price',
        'source',
        'store_id',
        'item_id'


    ];

    public function stores()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }
    public function store()
    {
        return $this->belongsTo(Store::class, 'store_id');
    }
}
