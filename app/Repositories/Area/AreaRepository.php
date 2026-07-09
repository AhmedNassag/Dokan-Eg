<?php

namespace App\Repositories\Area;

use App\Http\Resources\Area\AreaResource;
use App\Models\Area;
use App\Repositories\Area\AreaInterface;
use App\Traits\ApiResponseTrait;

class AreaRepository implements AreaInterface
{
    use ApiResponseTrait;

    public function getModel()
    {
        return new Area();
    }


    
    public function index($request, $filter): \Illuminate\Http\JsonResponse
    {
        $perPage = $request['per_page'] ?? config('pagination.per_page');

        $query = $this->getModel()
            ->with('city')
            ->ordering($request->ordering)
            ->filter($filter);

        $data = $perPage == -1
            ? $query->get()
            : $query->paginate($perPage);

        return $this->isOk(__('area'))
            ->setData(
                $perPage == -1
                    ? AreaResource::collection($data)
                    : $this->api_model_set_paginate(
                        AreaResource::collection($data),
                        $data
                    )
            )
            ->build();
    }



    public function store($request)
    {
        try {
            $area = $this->getModel()->create($request->validated());
            $area->load('city');
            return $this->isOk(__('Stored Successfully'))
                ->setData(AreaResource::make($area))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }



    public function show($id)
    {
        $area = $this->getModel()->with('city')->find($id);

        if (!$area) {
            return $this->isError(__('area Not Found'))
                ->setStatus(404)
                ->build();
        }
        return $this->isOk(__('area Data'))
            ->setData(AreaResource::make($area))
            ->build();
    }



    public function update($id, $request)
    {
        try {
            $area = $this->getModel()->find($id);
            if (!$area) {
                return $this->isError(__('area Not Found'))
                    ->setStatus(404)
                    ->build();
            }
            $area->update($request->validated());
            $area->load('city');
            return $this->isOk(__('Updated Successfully'))
                ->setData(AreaResource::make($area))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }



    public function destroy($id)
    {
        $area = $this->getModel()->find($id);

        if (!$area) {
            return $this->isError(__('area Not Found'))
                ->setStatus(404)
                ->build();
        }

        $area->delete();

        return $this->isOk(__('Destroyed Successfully'))
            ->build();
    }
}
