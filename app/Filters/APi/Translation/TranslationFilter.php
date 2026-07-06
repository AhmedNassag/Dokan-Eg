<?php

namespace App\Filters\Api\Translation;

use App\Filters\Filters;

class TranslationFilter extends Filters
{
    protected array $allowedFilters = ['language_id', 'group', 'key'];
}
