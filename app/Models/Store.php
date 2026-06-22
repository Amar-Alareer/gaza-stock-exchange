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

    public function regions(){
        return $this->belongsTo(Region::class);
      }


      public function prices()
      {
          return $this->hasMany(Price::class, 'store_id');
      }



      public function reports(){
        return $this->hasMany(Report::class);
    }
}
