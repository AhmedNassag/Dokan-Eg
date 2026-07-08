<?php

namespace App\Repositories\Brand;

use App\Http\Resources\Brand\BrandResource;
use App\Models\Brand;
use App\Repositories\Brand\BrandInterface;
use App\Traits\ApiResponseTrait;

class BrandRepository implements BrandInterface
{
    use ApiResponseTrait;

    public function getModel()
    {
        return new Brand();
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

        return $this->isOk(__('brand'))
            ->setData(
                $perPage == -1
                    ? BrandResource::collection($data)
                    : $this->api_model_set_paginate(
                        BrandResource::collection($data),
                        $data
                    )
            )
            ->build();
    }

    public function store($request)
    {
        try {
            $brand = $this->getModel()->create($request->validated());
            return $this->isOk(__('Stored Successfully'))
                ->setData(BrandResource::make($brand))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }

    public function show($brandId)
    {
        $brand = $this->getModel()->find($brandId);

        if (!$brand) {
            return $this->isError(__('brand Not Found'))
                ->setStatus(404)
                ->build();
        }
        return $this->isOk(__('brand Data'))
            ->setData(BrandResource::make($brand))
            ->build();
    }

    public function update($brandId, $request)
    {
        try {
            $brand = $this->getModel()->find($brandId);
            if (!$brand) {
                return $this->isError(__('brand Not Found'))
                    ->setStatus(404)
                    ->build();
            }
            $brand->update($request->validated());
            return $this->isOk(__('Updated Successfully'))
                ->setData(BrandResource::make($brand))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }

    public function destroy($brandId)
    {
        $brand = $this->getModel()->find($brandId);

        if (!$brand) {
            return $this->isError(__('brand Not Found'))
                ->setStatus(404)
                ->build();
        }

        $brand->delete();

        return $this->isOk(__('Destroyed Successfully'))
            ->build();
    }
}
