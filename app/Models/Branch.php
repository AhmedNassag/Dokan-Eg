<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Branch extends Model
{
    use SoftDeletes;

    protected $table = 'branches';

    protected $fillable = [
        'name',
        'status',
        'code',
        'mobile',
        'address',
        'area_id',
    ];

    public function area()
    {
        return $this->belongsTo(Area::class);
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
