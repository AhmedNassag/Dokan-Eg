<?php

namespace App\Repositories\Unit;

use App\Http\Resources\Unit\UnitResource;
use App\Models\Unit;
use App\Repositories\Unit\UnitInterface;
use App\Traits\ApiResponseTrait;

class UnitRepository implements UnitInterface
{
    use ApiResponseTrait;

    public function getModel()
    {
        return new Unit();
    }

    public function index($request, $filter): \Illuminate\Http\JsonResponse
    {
        $perPage = $request['per_page'] ?? config('pagination.per_page');

        $query = $this->getModel()
            ->with('baseUnit')
            ->ordering($request->ordering)
            ->filter($filter);

        $data = $perPage == -1
            ? $query->get()
            : $query->paginate($perPage);

        return $this->isOk(__('unit'))
            ->setData(
                $perPage == -1
                    ? UnitResource::collection($data)
                    : $this->api_model_set_paginate(
                        UnitResource::collection($data),
                        $data
                    )
            )
            ->build();
    }

    public function store($request)
    {
        try {
            $unit = $this->getModel()->create($request->validated());
            $unit->load('baseUnit');
            return $this->isOk(__('Stored Successfully'))
                ->setData(UnitResource::make($unit))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }

    public function show($unitId)
    {
        $unit = $this->getModel()->with('baseUnit')->find($unitId);

        if (!$unit) {
            return $this->isError(__('unit Not Found'))
                ->setStatus(404)
                ->build();
        }
        return $this->isOk(__('unit Data'))
            ->setData(UnitResource::make($unit))
            ->build();
    }

    public function update($unitId, $request)
    {
        try {
            $unit = $this->getModel()->find($unitId);
            if (!$unit) {
                return $this->isError(__('unit Not Found'))
                    ->setStatus(404)
                    ->build();
            }
            $unit->update($request->validated());
            $unit->load('baseUnit');
            return $this->isOk(__('Updated Successfully'))
                ->setData(UnitResource::make($unit))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }

    public function destroy($unitId)
    {
        $unit = $this->getModel()->find($unitId);

        if (!$unit) {
            return $this->isError(__('unit Not Found'))
                ->setStatus(404)
                ->build();
        }

        $unit->delete();

        return $this->isOk(__('Destroyed Successfully'))
            ->build();
    }
}
