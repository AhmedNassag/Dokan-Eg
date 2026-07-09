<?php

namespace App\Filters\Api\Translation;

use App\Filters\Filters;

class TranslationFilter extends Filters
{
    protected $var_filters = ['language_id', 'group', 'key'];

    public function language_id($value)
    {
        return $this->builder->where('language_id', $value);
    }

    public function group($value)
    {
        if ($value === '' || $value === null) {
            return $this->builder->whereNull('group');
        }
        return $this->builder->where('group', $value);
    }

    public function key($value)
    {
        return $this->builder->where('key', $value);
    }
}
