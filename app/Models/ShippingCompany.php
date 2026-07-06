<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShippingCompany extends Model
{
    use SoftDeletes;

    protected $table = 'shipping_companies';

    protected $fillable = [
        'name',
        'code',
        'phone',
        'status',
    ];

    public function prices()
    {
        return $this->hasMany(ShippingCompanyPrice::class);
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
