<?php

namespace App\Repositories\City;

use App\Http\Resources\City\CityResource;
use App\Models\City;
use App\Repositories\City\CityInterface;
use App\Traits\ApiResponseTrait;

class CityRepository implements CityInterface
{
    use ApiResponseTrait;

    public function getModel()
    {
        return new City();
    }

    public function index($request, $filter): \Illuminate\Http\JsonResponse
    {
        $perPage = $request['per_page'] ?? config('pagination.per_page');

        $query = $this->getModel()
            ->with('country')
            ->ordering($request->ordering)
            ->filter($filter);

        $data = $perPage == -1
            ? $query->get()
            : $query->paginate($perPage);

        return $this->isOk(__('city'))
            ->setData(
                $perPage == -1
                    ? CityResource::collection($data)
                    : $this->api_model_set_paginate(
                        CityResource::collection($data),
                        $data
                    )
            )
            ->build();
    }

    public function store($request)
    {
        try {
            $city = $this->getModel()->create($request->validated());
            $city->load('country');
            return $this->isOk(__('Stored Successfully'))
                ->setData(CityResource::make($city))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }

    public function show($cityId)
    {
        $city = $this->getModel()->with('country')->find($cityId);

        if (!$city) {
            return $this->isError(__('city Not Found'))
                ->setStatus(404)
                ->build();
        }
        return $this->isOk(__('city Data'))
            ->setData(CityResource::make($city))
            ->build();
    }

    public function update($cityId, $request)
    {
        try {
            $city = $this->getModel()->find($cityId);
            if (!$city) {
                return $this->isError(__('city Not Found'))
                    ->setStatus(404)
                    ->build();
            }
            $city->update($request->validated());
            $city->load('country');
            return $this->isOk(__('Updated Successfully'))
                ->setData(CityResource::make($city))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }

    public function destroy($cityId)
    {
        $city = $this->getModel()->find($cityId);

        if (!$city) {
            return $this->isError(__('city Not Found'))
                ->setStatus(404)
                ->build();
        }

        $city->delete();

        return $this->isOk(__('Destroyed Successfully'))
            ->build();
    }
}
