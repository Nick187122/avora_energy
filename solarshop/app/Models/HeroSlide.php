<?php

namespace App\Models;

use Database\Factories\HeroSlideFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HeroSlide extends Model
{
    /** @use HasFactory<HeroSlideFactory> */
    use HasFactory;

    protected $fillable = [
        'title', 'subtitle', 'description', 'image', 'button_text', 'button_url', 'sort_order', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}