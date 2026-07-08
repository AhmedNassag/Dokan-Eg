<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Unit extends Model
{
    use SoftDeletes;

    protected $table = 'units';

    protected $fillable = [
        'name',
        'status',
        'code',
        'short_name',
        'base_unit',
        'operator',
        'operator_value',
    ];

    public function baseUnit()
    {
        return $this->belongsTo(Unit::class, 'base_unit');
    }

    public function childUnits()
    {
        return $this->hasMany(Unit::class, 'base_unit');
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
