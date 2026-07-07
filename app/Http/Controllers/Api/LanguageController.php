<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Language\LanguageFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreLanguageRequest;
use App\Http\Requests\Language\UpdateLanguageRequest;
use App\Repositories\Language\LanguageInterface;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    protected $language;

    public function __construct(LanguageInterface $language)
    {
        $this->language = $language;
    }

    public function index(Request $request, LanguageFilter $filter)
    {
        return $this->language->index($request, $filter);
    }

    public function show($id)
    {
        return $this->language->show($id);
    }

    public function store(StoreLanguageRequest $request)
    {
        return $this->language->store($request);
    }

    public function update($id, UpdateLanguageRequest $request)
    {
        return $this->language->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->language->destroy($id);
    }
}
