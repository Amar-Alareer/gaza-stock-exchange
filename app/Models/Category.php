<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'image',
        'is_active',
        'sort_order',
    ];

    protected $appends = ['image_url'];

    public function getImageUrlAttribute(): ?string
    {
        if (! $this->image) {
            return null;
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

    public function items(): HasMany
    {
        return $this->hasMany(Item::class);
    }
}
