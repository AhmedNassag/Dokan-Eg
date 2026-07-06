<?php

namespace App\Repositories\Branch;

use App\Http\Resources\Branch\BranchResource;
use App\Models\Branch;
use App\Repositories\Branch\BranchInterface;
use App\Traits\ApiResponseTrait;

class BranchRepository implements BranchInterface
{
    use ApiResponseTrait;

    public function getModel()
    {
        return new Branch();
    }

    public function index($request, $filter): \Illuminate\Http\JsonResponse
    {
        $perPage = $request['per_page'] ?? config('pagination.per_page');

        $query = $this->getModel()
            ->with('area')
            ->ordering($request->ordering)
            ->filter($filter);

        $data = $perPage == -1
            ? $query->get()
            : $query->paginate($perPage);

        return $this->isOk(__('branch'))
            ->setData(
                $perPage == -1
                    ? BranchResource::collection($data)
                    : $this->api_model_set_paginate(
                        BranchResource::collection($data),
                        $data
                    )
            )
            ->build();
    }

    public function store($request)
    {
        try {
            $branch = $this->getModel()->create($request->validated());
            $branch->load('area');
            return $this->isOk(__('Stored Successfully'))
                ->setData(BranchResource::make($branch))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }

    public function show($branchId)
    {
        $branch = $this->getModel()->with('area')->find($branchId);

        if (!$branch) {
            return $this->isError(__('branch Not Found'))
                ->setStatus(404)
                ->build();
        }
        return $this->isOk(__('branch Data'))
            ->setData(BranchResource::make($branch))
            ->build();
    }

    public function update($branchId, $request)
    {
        try {
            $branch = $this->getModel()->find($branchId);
            if (!$branch) {
                return $this->isError(__('branch Not Found'))
                    ->setStatus(404)
                    ->build();
            }
            $branch->update($request->validated());
            $branch->load('area');
            return $this->isOk(__('Updated Successfully'))
                ->setData(BranchResource::make($branch))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }

    public function destroy($branchId)
    {
        $branch = $this->getModel()->find($branchId);

        if (!$branch) {
            return $this->isError(__('branch Not Found'))
                ->setStatus(404)
                ->build();
        }

        $branch->delete();

        return $this->isOk(__('Destroyed Successfully'))
            ->build();
    }
}
