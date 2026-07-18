<?php

namespace App\Filters\Api\Shop;

use App\Filters\Filters;

class ShopFilter extends Filters
{
    protected $var_filters = [
        'name',
        'is_active',
        'is_featured',
        'user_id',
    ];

    public function name($value)
    {
        return $this->builder->where('name', 'LIKE', "%$value%");
    }

    public function is_active($value)
    {
        return $this->builder->where('is_active', $value);
    }

    public function is_featured($value)
    {
        return $this->builder->where('is_featured', $value);
    }

    public function user_id($value)
    {
        return $this->builder->where('user_id', $value);
    }
}
