<?php

namespace App\Filters\Api\ShippingCompany;

use App\Filters\Filters;

class ShippingCompanyFilter extends Filters
{
    protected $var_filters = [
        'name',
        'code',
        'status',
    ];

    public function name($value)
    {
        return $this->builder->where('name', 'LIKE', "%$value%");
    }

    public function code($value)
    {
        return $this->builder->where('code', 'LIKE', "%$value%");
    }

    public function status($value)
    {
        return $this->builder->where('status', $value);
    }
}
