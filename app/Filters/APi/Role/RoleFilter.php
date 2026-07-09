<?php

namespace App\Filters\Api\Role;

use App\Filters\Filters;

class RoleFilter extends Filters
{
    protected $var_filters = [
        'q',
    ];

    public function q($value)
    {
        return $this->builder->where('name', 'LIKE', "%$value%");
    }
}
