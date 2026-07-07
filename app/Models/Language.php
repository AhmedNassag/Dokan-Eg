<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    protected $table = 'languages';

    protected $fillable = [
        'name',
        'code',
        'direction',
        'status',
    ];

    public function translations()
    {
        return $this->hasMany(Translation::class);
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
