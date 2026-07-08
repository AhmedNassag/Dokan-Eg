<?php

namespace App\Filters\Api\Unit;

use App\Filters\Filters;

class UnitFilter extends Filters
{
    protected $var_filters = [
        'name',
        'status',
        'code',
        'short_name',
        'base_unit',
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

    public function short_name($value)
    {
        return $this->builder->where('short_name', 'LIKE', "%$value%");
    }

    public function base_unit($value)
    {
        return $this->builder->where('base_unit', $value);
    }
}
