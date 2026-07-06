<?php

namespace App\Filters\Api\City;

use App\Filters\Filters;

class CityFilter extends Filters
{
    protected $var_filters = [
        'name',
        'status',
        'country_id',
    ];

    public function name($value)
    {
        return $this->builder->where('name', 'LIKE', "%$value%");
    }

    public function status($value)
    {
        return $this->builder->where('status', $value);
    }

    public function country_id($value)
    {
        return $this->builder->where('country_id', $value);
    }
}
