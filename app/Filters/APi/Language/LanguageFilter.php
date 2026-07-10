<?php

namespace App\Filters\Api\Language;

use App\Filters\Filters;

class LanguageFilter extends Filters
{
    protected $var_filters = [
        'name',
        'code',
        'status',
        'is_default',
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

    public function is_default($value)
    {
        return $this->builder->where('is_default', $value);
    }
}
