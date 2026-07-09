<?php

namespace App\Filters\Api\Category;

use App\Filters\Filters;

class CategoryFilter extends Filters
{
    protected $var_filters = [
        'q',
        'is_active',
    ];

    public function q($value)
    {
        return $this->builder->where(function ($q) use ($value) {
            $q->where('name', 'LIKE', "%$value%")
              ->orWhere('description', 'LIKE', "%$value%");
        });
    }

    public function is_active($value)
    {
        return $this->builder->where('is_active', $value);
    }
}
