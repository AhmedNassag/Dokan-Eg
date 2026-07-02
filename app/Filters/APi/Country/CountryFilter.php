<?php

namespace App\Filters\Api\Country;

use App\Filters\Filters;

class CountryFilter extends Filters
{
    protected $var_filters = [
        'name',
        'status',
    ];

    public function name($value)
    {
        return $this->builder->where('name', 'LIKE', "%$value%");
    }
    public function status($value)
    {
        return $this->builder->where('status', $value);
    }
}
