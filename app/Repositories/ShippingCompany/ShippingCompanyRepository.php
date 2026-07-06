<?php

namespace App\Repositories\ShippingCompany;

use App\Http\Resources\ShippingCompany\ShippingCompanyResource;
use App\Models\ShippingCompany;
use App\Models\ShippingCompanyPrice;
use App\Repositories\ShippingCompany\ShippingCompanyInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;

class ShippingCompanyRepository implements ShippingCompanyInterface
{
    use ApiResponseTrait;

    public function getModel()
    {
        return new ShippingCompany();
    }

    public function index($request, $filter): \Illuminate\Http\JsonResponse
    {
        $perPage = $request['per_page'] ?? config('pagination.per_page');

        $query = $this->getModel()
            ->with(['prices.city'])
            ->ordering($request->ordering)
            ->filter($filter);

        $data = $perPage == -1
            ? $query->get()
            : $query->paginate($perPage);

        return $this->isOk(__('shipping_companies'))
            ->setData(
                $perPage == -1
                    ? ShippingCompanyResource::collection($data)
                    : $this->api_model_set_paginate(
                        ShippingCompanyResource::collection($data),
                        $data
                    )
            )
            ->build();
    }

    public function store($request)
    {
        try {
            DB::beginTransaction();

            $company = $this->getModel()->create($request->validated());

            if ($request->has('prices')) {
                foreach ($request->prices as $priceData) {
                    ShippingCompanyPrice::create([
                        'shipping_company_id' => $company->id,
                        'city_id' => $priceData['city_id'],
                        'price' => $priceData['price'],
                    ]);
                }
            }

            $company->load('prices.city');

            DB::commit();

            return $this->isOk(__('Stored Successfully'))
                ->setData(ShippingCompanyResource::make($company))
                ->build();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->isError('An Error occured')->setStatus(500)->build();
        }
    }

    public function show($id)
    {
        $company = $this->getModel()->with('prices.city')->find($id);
        if (!$company) {
            return $this->isError(__('Not Found'))->setStatus(404)->build();
        }
        return $this->isOk(__('Data'))
            ->setData(ShippingCompanyResource::make($company))
            ->build();
    }

    public function update($id, $request)
    {
        try {
            DB::beginTransaction();

            $company = $this->getModel()->find($id);
            if (!$company) {
                return $this->isError(__('Not Found'))->setStatus(404)->build();
            }

            $company->update($request->validated());

            if ($request->has('prices')) {
                $company->prices()->delete();
                foreach ($request->prices as $priceData) {
                    ShippingCompanyPrice::create([
                        'shipping_company_id' => $company->id,
                        'city_id' => $priceData['city_id'],
                        'price' => $priceData['price'],
                    ]);
                }
            }

            $company->load('prices.city');

            DB::commit();

            return $this->isOk(__('Updated Successfully'))
                ->setData(ShippingCompanyResource::make($company))
                ->build();
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->isError('An Error occured')->setStatus(500)->build();
        }
    }

    public function destroy($id)
    {
        $company = $this->getModel()->find($id);
        if (!$company) {
            return $this->isError(__('Not Found'))->setStatus(404)->build();
        }
        $company->delete();
        return $this->isOk(__('Destroyed Successfully'))->build();
    }
}
