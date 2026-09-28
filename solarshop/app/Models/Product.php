<?php

namespace App\Models;

use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory;

    protected $fillable = [
        'category_id', 'brand_id', 'name', 'slug', 'sku', 'short_description',
        'description', 'regular_price', 'sale_price', 'stock', 'image', 'gallery',
        'specifications', 'is_featured', 'is_active', 'meta_title', 'meta_description', 'views',
    ];

    protected $casts = [
        'gallery' => 'array',
        'specifications' => 'array',
        'is_featured' => 'boolean',
        'is_active' => 'boolean',
        'regular_price' => 'decimal:2',
        'sale_price' => 'decimal:2',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function brand(): BelongsTo
    {
        return $this->belongsTo(Brand::class);
    }

    public function getDisplayPriceAttribute(): float
    {
        return $this->sale_price ? (float) $this->sale_price : (float) $this->regular_price;
    }

    public function getHasSaleAttribute(): bool
    {
        return $this->sale_price && $this->sale_price < $this->regular_price;
    }

    public function getDiscountPercentAttribute(): int
    {
        if (! $this->has_sale) {
            return 0;
        }
        return (int) round((1 - (float) $this->sale_price / (float) $this->regular_price) * 100);
    }

    public function getIsInStockAttribute(): bool
    {
        return $this->stock > 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeOnSale($query)
    {
        return $query->whereNotNull('sale_price')->whereColumn('sale_price', '<', 'regular_price');
    }
}