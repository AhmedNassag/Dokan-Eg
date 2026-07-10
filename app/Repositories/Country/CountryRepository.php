<?php

namespace App\Repositories\Country;


use App\Http\Resources\CountryResource;
use App\Models\Country;
use App\Repositories\Country\CountryInterface;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\Cache;



class CountryRepository implements CountryInterface
{
    use ApiResponseTrait;

    public function getModel()
    {
        return new Country();
    }



    public function index($request/*, $filter*/): \Illuminate\Http\JsonResponse
    {
        $perPage = $request['per_page'] ?? config('pagination.per_page');

        $query = $this->getModel()
            ->ordering($request->ordering)
            // ->filter($filter)
            ;

        $data = $perPage == -1
            ? $query->get()
            : $query->paginate($perPage);

        return $this->isOk(__('country'))
            ->setData(
                $perPage == -1
                    ? CountryResource::collection($data)
                    : $this->api_model_set_paginate(
                        CountryResource::collection($data),
                        $data
                    )
            )
            ->build();
    }



    public function store($request)
    {
        try {
            $country = $this->getModel()->create($request->validated());
            return $this->isOk(__('Stored Successfully'))
                ->setData(CountryResource::make($country))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }



    public function show($id)
    {
        $country = $this->getModel()->find($id);

        if (!$country) {
            return $this->isError(__('country Not Found'))
                ->setStatus(404)
                ->build();
        }
        return $this->isOk(__('country Data'))
            ->setData(countryResource::make($country))
            ->build();
    }



    public function update($id, $request)
    {
        try {
            $country = $this->getModel()->find($id);
            if (!$country) {
                return $this->isError(__('country Not Found'))
                    ->setStatus(404)
                    ->build();
            }
            $country->update($request->validated());
            return $this->isOk(__('Updated Successfully'))
                ->setData(CountryResource::make($country))
                ->build();
        } catch (\Exception $e) {
            return $this->isError('An Error occured')
                ->setStatus(500)
                ->build();
        }
    }


    
    public function destroy($id)
    {
        $country = $this->getModel()->find($id);

        if (!$country) {
            return $this->isError(__('country Not Found'))
                ->setStatus(404)
                ->build();
        }

        $country->delete();

        return $this->isOk(__('Destroyed Successfully'))
            ->build();
    }
}
