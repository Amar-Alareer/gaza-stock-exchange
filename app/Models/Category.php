<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
 protected $fillable=[
'name',
'slug',
'description',
'image',
'is_active',
'sort_order',

 ];

 public function items(): HasMany
    {
        return $this->hasMany(item::class);
    }


}
