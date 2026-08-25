<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'latitude',
        'longitude',
        'facebook_url',
        'instagram_url',
        'telegram_url',
        'region_id'
    ];

    public function region()
{
    return $this->belongsTo(Region::class, 'region_id');
}
    public function prices()
    {
        return $this->hasMany(Price::class, 'store_id');
    }
    public function complaints()
    {
        return $this->hasMany(Complaints::class, 'store_id');
    }
    
}
