<?php

namespace App\Filters\Api\Branch;

use App\Filters\Filters;

class BranchFilter extends Filters
{
    protected $var_filters = [
        'name',
        'status',
        'code',
        'area_id',
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

    public function area_id($value)
    {
        return $this->builder->where('area_id', $value);
    }
}
