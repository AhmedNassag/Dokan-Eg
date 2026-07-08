<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Language\LanguageFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreLanguageRequest;
use App\Http\Requests\Language\UpdateLanguageRequest;
use App\Repositories\Language\LanguageInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class LanguageController extends Controller
{
    protected $language;

    public function __construct(LanguageInterface $language)
    {
        $this->language = $language;
    }
    public static function middleware(): array
    {
        return [
            'auth:sanctum',

            new Middleware('permission:list-language', only: ['index']),
            new Middleware('permission:store-language', only: ['store']),
            new Middleware('permission:show-language', only: ['show']),
            new Middleware('permission:update-language', only: ['update']),
            new Middleware('permission:destroy-language', only: ['destroy']),
        ];
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