<?php

namespace App\Filters\Api\Area;

use App\Filters\Filters;

class AreaFilter extends Filters
{
    protected $var_filters = [
        'name',
        'status',
        'city_id',
    ];

    public function name($value)
    {
        return $this->builder->where('name', 'LIKE', "%$value%");
    }

    public function status($value)
    {
        return $this->builder->where('status', $value);
    }

    public function city_id($value)
    {
        return $this->builder->where('city_id', $value);
    }
}
