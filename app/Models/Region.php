<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Region extends Model
{
    protected $fillable = [
        'city_or_governorate',
        'area_name'
    ];

    public function users(){
      return $this->hasMany(User::class);
    }


    public function stores(){
        return $this->hasMany(Store::class);
      }
}
