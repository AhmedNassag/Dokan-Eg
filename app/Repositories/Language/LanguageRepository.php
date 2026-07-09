<?php

namespace App\Repositories\Language;

use App\Http\Resources\Language\LanguageResource;
use App\Models\Language;
use App\Repositories\Language\LanguageInterface;
use App\Traits\ApiResponseTrait;

class LanguageRepository implements LanguageInterface
{
    use ApiResponseTrait;

    public function getModel()
    {
        return new Language();
    }



    public function index($request, $filter): \Illuminate\Http\JsonResponse
    {
        $perPage = $request['per_page'] ?? config('pagination.per_page');

        $query = $this->getModel()
            ->ordering($request->ordering)
            ->filter($filter);

        $data = $perPage == -1
            ? $query->get()
            : $query->paginate($perPage);

        return $this->isOk(__('languages'))
            ->setData(
                $perPage == -1
                    ? LanguageResource::collection($data)
                    : $this->api_model_set_paginate(
                        LanguageResource::collection($data),
                        $data
                    )
            )
            ->build();
    }



    public function store($request)
    {
        $language = $this->getModel()->create($request->validated());
        return $this->isOk(__('Stored Successfully'))
            ->setData(LanguageResource::make($language))
            ->build();
    }



    public function show($id)
    {
        $language = $this->getModel()->find($id);
        if (!$language) {
            return $this->isError(__('Not Found'))->setStatus(404)->build();
        }
        return $this->isOk(__('Data'))
            ->setData(LanguageResource::make($language))
            ->build();
    }



    public function update($id, $request)
    {
        $language = $this->getModel()->find($id);
        if (!$language) {
            return $this->isError(__('Not Found'))->setStatus(404)->build();
        }
        $language->update($request->validated());
        return $this->isOk(__('Updated Successfully'))
            ->setData(LanguageResource::make($language))
            ->build();
    }


    
    public function destroy($id)
    {
        $language = $this->getModel()->find($id);
        if (!$language) {
            return $this->isError(__('Not Found'))->setStatus(404)->build();
        }
        $language->delete();
        return $this->isOk(__('Destroyed Successfully'))->build();
    }
}
