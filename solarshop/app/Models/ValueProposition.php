<?php

namespace App\Models;

use Database\Factories\ValuePropositionFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ValueProposition extends Model
{
    /** @use HasFactory<ValuePropositionFactory> */
    use HasFactory;

    protected $fillable = ['title', 'subtitle', 'icon', 'sort_order', 'is_active'];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
