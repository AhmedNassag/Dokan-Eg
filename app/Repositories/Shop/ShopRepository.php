<?php

namespace App\Repositories\Shop;

use App\Http\Resources\Shop\ShopResource;
use App\Models\Shop;
use App\Repositories\Shop\ShopInterface;
use App\Traits\ApiResponseTrait;

class ShopRepository implements ShopInterface
{
    use ApiResponseTrait;

    public function getModel()
    {
        return new Shop();
    }



    public function index($request): \Illuminate\Http\JsonResponse
    {
        $perPage = $request['per_page'] ?? config('pagination.per_page');

        $query = $this->getModel()
            ->with('user')
            ->ordering($request->ordering);

        $data = $perPage == -1
            ? $query->get()
            : $query->paginate($perPage);

        return $this->isOk(__('shop'))
            ->setData(
                $perPage == -1
                    ? ShopResource::collection($data)
                    : $this->api_model_set_paginate(
                        ShopResource::collection($data),
                        $data
                    )
            )
            ->build();
    }



    public function store($data)
    {
        try {
            $shop = $this->getModel()->create($data);
            $shop->load('user');

            return $this->isOk(__('Stored Successfully'))
                ->setData(ShopResource::make($shop))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }



    public function show($id)
    {
        $shop = $this->getModel()->with('user')->find($id);

        if (!$shop) {
            return $this->isError(__('shop Not Found'))
                ->setStatus(404)
                ->build();
        }

        return $this->isOk(__('shop Data'))
            ->setData(ShopResource::make($shop))
            ->build();
    }



    public function update($id, $data)
    {
        try {
            $shop = $this->getModel()->find($id);
            if (!$shop) {
                return $this->isError(__('shop Not Found'))
                    ->setStatus(404)
                    ->build();
            }

            $shop->update($data);
            $shop->load('user');

            return $this->isOk(__('Updated Successfully'))
                ->setData(ShopResource::make($shop))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }



    public function destroy($id)
    {
        $shop = $this->getModel()->find($id);

        if (!$shop) {
            return $this->isError(__('shop Not Found'))
                ->setStatus(404)
                ->build();
        }

        $shop->delete();

        return $this->isOk(__('Destroyed Successfully'))
            ->build();
    }
}
