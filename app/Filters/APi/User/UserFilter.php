<?php

namespace App\Filters\Api\User;

use App\Filters\Filters;

class UserFilter extends Filters
{
    protected $var_filters = [
        'q',
    ];

    public function q($value)
    {
        return $this->builder->where(function ($q) use ($value) {
            $q->where('name', 'LIKE', "%$value%")
              ->orWhere('email', 'LIKE', "%$value%");
        });
    }
}
