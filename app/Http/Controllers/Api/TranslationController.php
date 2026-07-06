<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Translation\TranslationFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Translation\StoreTranslationRequest;
use App\Http\Requests\Translation\UpdateTranslationRequest;
use App\Repositories\Translation\TranslationInterface;
use Illuminate\Http\Request;

class TranslationController extends Controller
{
    protected $translation;

    public function __construct(TranslationInterface $translation)
    {
        $this->translation = $translation;
    }

    public function index(Request $request, TranslationFilter $filter)
    {
        return $this->translation->index($request, $filter);
    }

    public function show($id)
    {
        return $this->translation->show($id);
    }

    public function store(StoreTranslationRequest $request)
    {
        return $this->translation->store($request);
    }

    public function update($id, UpdateTranslationRequest $request)
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
