<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Translation\TranslationFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Translation\StoreRequest;
use App\Http\Requests\Translation\UpdateRequest;
use App\Repositories\Translation\TranslationInterface;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected $translation;

    public function __construct(TranslationInterface $translation)
    {
        $this->translation = $translation;

        $this->middleware('auth:sanctum', ['except' => ['export']]);

        $this->middleware('permission:list-translation', ['only' => ['index']]);
        $this->middleware('permission:show-translation', ['only' => ['show']]);
        $this->middleware('permission:store-translation', ['only' => ['store']]);
        $this->middleware('permission:update-translation', ['only' => ['update']]);
        $this->middleware('permission:destroy-translation', ['only' => ['destroy']]);
    }



    public function index(Request $request/*, TranslationFilter $filter*/)
    {
        return $this->translation->index($request/*, $filter*/);
    }



    public function show($id)
    {
        return $this->translation->show($id);
    }



    public function store(StoreRequest $request)
    {
        return $this->translation->store($request);
    }



    public function update($id, UpdateRequest $request)
    {
        return $this->translation->update($id, $request);
    }



    public function destroy($id)
    {
        return $this->translation->destroy($id);
    }



    public function export(Request $request)
    {
        $code = $request->get('code', 'en');
        return $this->translation->export($code);
    }
}
