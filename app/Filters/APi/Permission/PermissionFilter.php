<?php

namespace App\Filters\Api\Permission;

use App\Filters\Filters;

class PermissionFilter extends Filters
{
    protected $var_filters = [
        'q',
    ];

    public function q($value)
    {
        return $this->builder->where('name', 'LIKE', "%$value%");
    }
}
