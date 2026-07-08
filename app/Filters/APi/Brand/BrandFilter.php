<?php

namespace App\Filters\Api\Brand;

use App\Filters\Filters;

class BrandFilter extends Filters
{
    protected $var_filters = [
        'name',
        'status',
        'code',
    ];

    public function name($value)
    {
        return $this->builder->where('name', 'LIKE', "%$value%");
    }

    public function status($value)
    {
        return $this->builder->where('status', $value);
    }

    public function code($value)
    {
        return $this->builder->where('code', 'LIKE', "%$value%");
    }
}
