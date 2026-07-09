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
        $data = $request->validated();

        $data['is_default'] = $request->boolean('is_default');

        if ($data['is_default']) {
            $data['status'] = true;
            $this->getModel()->where('is_default', true)->update(['is_default' => false]);
        }

        $language = $this->getModel()->create($data);

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

        $data = $request->validated();

        if ($request->has('status')) {
            $newStatus = $request->boolean('status');

            if (!$newStatus && $language->is_default) {
                return $this->isError(__('Cannot deactivate the default language'))
                    ->setStatus(400)
                    ->build();
            }

            $data['status'] = $newStatus;
        }

        $language->update($data);

        return $this->isOk(__('Updated Successfully'))
            ->setData(LanguageResource::make($language))
            ->build();
    }

    public function setDefault($id)
    {
        $language = $this->getModel()->find($id);
        if (!$language) {
            return $this->isError(__('Not Found'))->setStatus(404)->build();
        }

        if ($language->is_default) {
            return $this->isError(__('This language is already the default'))
                ->setStatus(400)
                ->build();
        }

        if (!$language->status) {
            return $this->isError(__('Cannot set an inactive language as default'))
                ->setStatus(400)
                ->build();
        }

        $this->getModel()->where('is_default', true)->update(['is_default' => false]);

        $language->update(['is_default' => true]);

        return $this->isOk(__('Default language updated successfully'))
            ->setData(LanguageResource::make($language))
            ->build();
    }

    public function destroy($id)
    {
        $language = $this->getModel()->find($id);
        if (!$language) {
            return $this->isError(__('Not Found'))->setStatus(404)->build();
        }

        if ($language->is_default) {
            return $this->isError(__('Cannot delete the default language'))
                ->setStatus(400)
                ->build();
        }

        $language->delete();

        return $this->isOk(__('Destroyed Successfully'))->build();
    }
}
