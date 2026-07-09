<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Language\LanguageFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Language\StoreRequest;
use App\Http\Requests\Language\UpdateRequest;
use App\Repositories\Language\LanguageInterface;
use Illuminate\Http\Request;

class LanguageController extends Controller
{
    protected $language;

    public function __construct(LanguageInterface $language)
    {
        $this->language = $language;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-language', ['only' => ['index']]);
        $this->middleware('permission:show-language', ['only' => ['show']]);
        $this->middleware('permission:store-language', ['only' => ['store']]);
        $this->middleware('permission:update-language', ['only' => ['update', 'setDefault']]);
        $this->middleware('permission:destroy-language', ['only' => ['destroy']]);
    }



    public function index(Request $request, LanguageFilter $filter)
    {
        return $this->language->index($request, $filter);
    }



    public function show($id)
    {
        return $this->language->show($id);
    }



    public function store(StoreRequest $request)
    {
        return $this->language->store($request);
    }



    public function update($id, UpdateRequest $request)
    {
        return $this->language->update($id, $request);
    }



    public function setDefault($id)
    {
        return $this->language->setDefault($id);
    }



    public function destroy($id)
    {
        return $this->language->destroy($id);
    }
}
