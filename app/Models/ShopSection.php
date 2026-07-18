<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ShopSection extends Model
{
    protected $table = 'shop_sections';

    protected $fillable = [
        'shop_id',
        'type',
        'title',
        'content',
        'settings',
        'position',
        'is_active',
    ];

    protected $casts = [
        'content'   => 'array',
        'settings'  => 'array',
        'is_active' => 'boolean',
    ];

    public function shop(): BelongsTo
    {
        return $this->belongsTo(Shop::class);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('position');
    }
}
