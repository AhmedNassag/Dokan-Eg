<?php

namespace App\Models;

use App\Enums\ShopTheme;
use App\Enums\UserType;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Shop extends Model
{
    use SoftDeletes;

    protected $table = 'shops';

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('user_shops', function (Builder $query) {
            if (
                Auth::check() &&
                Auth::user()->user_type !== UserType::ADMIN
            ) {
                $query->where('user_id', Auth::id());
            }
        });
    }

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'description',
        'logo',
        'cover',
        'theme',
        'primary_color',
        'secondary_color',
        'font_family',
        'phone',
        'email',
        'website',
        'social_links',
        'meta_title',
        'meta_description',
        'is_active',
        'is_featured',
        'published_at',
    ];

    protected $casts = [
        'theme'        => ShopTheme::class,
        'social_links' => 'array',
        'is_active'    => 'boolean',
        'is_featured'  => 'boolean',
        'published_at' => 'datetime',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sections(): HasMany
    {
        return $this->hasMany(ShopSection::class)->ordered();
    }

    public function scopeFilter($query, $filter)
    {
        return $filter->apply($query);
    }

    public function scopeOrdering($query, $ordering = null)
    {
        if (!$ordering) {
            return $query->orderBy('created_at', 'desc');
        }

        if ($ordering == 'oldest') {
            return $query->orderBy('created_at', 'asc');
        }

        return $query->orderBy('created_at', 'desc');
    }
}