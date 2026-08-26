<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'price',
        'unit',
        'store_name',
        'category',
        'image_url',
        'min_price',
        'category_id',
    ];

    protected $appends = [
        'best_price',
        'best_store',
        'category_name',
        'formatted_updated_at',
        'formatted_image_url',
    ];

    public function getFormattedImageUrlAttribute(): ?string
    {
        if (!$this->image_url) {
            return null;
        }

        $img = trim($this->image_url);

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

        return asset('storage/' . ltrim($img, '/'));
    }

    public function prices()
    {
        return $this->hasMany(Price::class, 'item_id');
    }

    public function categoryRelation()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getBestPriceAttribute()
    {
        if ($this->min_price !== null && $this->min_price > 0) {
            return $this->min_price;
        }
        if ($this->relationLoaded('prices') && $this->prices->isNotEmpty()) {
            return $this->prices->min('price');
        }
        if (!empty($this->price) && $this->price > 0) {
            return $this->price;
        }
        return $this->prices()->min('price') ?? null;
    }

    public function getBestStoreAttribute()
    {
        if (!empty($this->store_name)) {
            return $this->store_name;
        }
        if ($this->relationLoaded('prices') && $this->prices->isNotEmpty()) {
            $cheapestPrice = $this->prices->sortBy('price')->first();
            return $cheapestPrice->store?->name ?? ($cheapestPrice->source ?? 'متجر محلي');
        }
        $cheapestPrice = $this->prices()->with('store')->orderBy('price', 'asc')->first();
        return $cheapestPrice->store?->name ?? ($cheapestPrice->source ?? 'متجر محلي');
    }

    public function getCategoryNameAttribute()
    {
        if ($this->relationLoaded('categoryRelation') && $this->categoryRelation) {
            return $this->categoryRelation->name;
        }
        return $this->category ?: ($this->categoryRelation?->name ?? 'عام');
    }

    public function getFormattedUpdatedAtAttribute()
    {
        return $this->updated_at ? $this->updated_at->locale('ar')->diffForHumans() : 'مؤخراً';
    }
}
