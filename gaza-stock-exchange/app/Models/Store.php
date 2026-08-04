<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Store extends Model
{
    protected $fillable = [
        'name',
        'phone',
        'working_hours',
        'latitude',
        'longitude',
        'facebook_url',
        'instagram_url',
        'telegram_url',
        'region_id',
        'address',
        'governorate',
        'sub_area',
        'image',
        'cover_image',
    ];

    /**
     * رابط الصورة الكامل
     */
    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
        }

        return url('storage/'.$this->image);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        return url('storage/'.$this->cover_image);
    }

    protected $appends = ['image_url', 'cover_image_url'];

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
