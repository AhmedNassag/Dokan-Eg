<?php

namespace App\Filters\Api\Language;

use App\Filters\Filters;

class LanguageFilter extends Filters
{
    protected array $allowedFilters = ['name', 'code', 'status'];
}
