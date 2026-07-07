<?php

namespace App\Repositories\Translation;

use App\Http\Resources\Translation\TranslationResource;
use App\Models\Language;
use App\Models\Translation;
use App\Repositories\Translation\TranslationInterface;
use App\Traits\ApiResponseTrait;

class TranslationRepository implements TranslationInterface
{
    use ApiResponseTrait;

    public function getModel()
    {
        return new Translation();
    }

    public function index($request, $filter): \Illuminate\Http\JsonResponse
    {
        $perPage = $request['per_page'] ?? config('pagination.per_page');

        $query = $this->getModel()
            ->with('language')
            ->ordering($request->ordering)
            ->filter($filter);

        $data = $perPage == -1
            ? $query->get()
            : $query->paginate($perPage);

        return $this->isOk(__('translations'))
            ->setData(
                $perPage == -1
                    ? TranslationResource::collection($data)
                    : $this->api_model_set_paginate(
                        TranslationResource::collection($data),
                        $data
                    )
            )
            ->build();
    }

    public function store($request)
    {
        $translation = $this->getModel()->create($request->validated());
        return $this->isOk(__('Stored Successfully'))
            ->setData(TranslationResource::make($translation))
            ->build();
    }

    public function show($id)
    {
        $translation = $this->getModel()->with('language')->find($id);
        if (!$translation) {
            return $this->isError(__('Not Found'))->setStatus(404)->build();
        }
        return $this->isOk(__('Data'))
            ->setData(TranslationResource::make($translation))
            ->build();
    }

    public function update($id, $request)
    {
        $translation = $this->getModel()->find($id);
        if (!$translation) {
            return $this->isError(__('Not Found'))->setStatus(404)->build();
        }
        $translation->update($request->validated());
        return $this->isOk(__('Updated Successfully'))
            ->setData(TranslationResource::make($translation))
            ->build();
    }

    public function destroy($id)
    {
        $translation = $this->getModel()->find($id);
        if (!$translation) {
            return $this->isError(__('Not Found'))->setStatus(404)->build();
        }
        $translation->delete();
        return $this->isOk(__('Destroyed Successfully'))->build();
    }

    public function export($code)
    {
        $language = Language::where('code', $code)->first();
        if (!$language) {
            return $this->isError('Language not found')->setStatus(404)->build();
        }

        $translations = $this->getModel()
            ->where('language_id', $language->id)
            ->get();

        $nested = [];
        foreach ($translations as $t) {
            $combinedKey = $t->group ? "{$t->group}.{$t->key}" : $t->key;
            $keys = explode('.', $combinedKey);
            $ref = &$nested;
            foreach ($keys as $i => $k) {
                if ($i === count($keys) - 1) {
                    $ref[$k] = $t->value;
                } else {
                    if (!isset($ref[$k])) {
                        $ref[$k] = [];
                    }
                    $ref = &$ref[$k];
                }
            }
            unset($ref);
        }

        return $this->isOk(__('translations'))
            ->setData($nested)
            ->build();
    }
}
