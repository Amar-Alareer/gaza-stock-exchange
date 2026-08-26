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

    protected $appends = ['image_url', 'cover_image_url'];

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return asset('assets/imges/shops.png');
        }

        $img = trim($this->image);

        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://') || str_starts_with($img, 'data:image')) {
            return $img;
        }

        if (str_starts_with($img, '/storage/')) {
            return asset(ltrim($img, '/'));
        }

        if (str_starts_with($img, 'storage/')) {
            return asset($img);
        }

        if (str_starts_with($img, 'assets/') || str_starts_with($img, '/assets/')) {
            return asset(ltrim($img, '/'));
        }

        return asset('storage/'.ltrim($img, '/'));
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        if (! $this->cover_image) {
            return null;
        }

        $img = trim($this->cover_image);

        if (str_starts_with($img, 'http://') || str_starts_with($img, 'https://') || str_starts_with($img, 'data:image')) {
            return $img;
        }

        if (str_starts_with($img, '/storage/')) {
            return asset(ltrim($img, '/'));
        }

        if (str_starts_with($img, 'storage/')) {
            return asset($img);
        }

        if (str_starts_with($img, 'assets/') || str_starts_with($img, '/assets/')) {
            return asset(ltrim($img, '/'));
        }

        return asset('storage/'.ltrim($img, '/'));
    }

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