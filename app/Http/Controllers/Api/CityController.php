<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\City\CityFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\City\StoreCityRequest;
use App\Http\Requests\City\UpdateCityRequest;
use App\Repositories\City\CityInterface;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected $city;

    public function __construct(CityInterface $city)
    {
        $this->city = $city;
    }

    public function index(Request $request, CityFilter $filter)
    {
        return $this->city->index($request, $filter);
    }

    public function show($cityId)
    {
        return $this->city->show($cityId);
    }

    public function store(StoreCityRequest $request)
    {
        return $this->city->store($request);
    }

    public function update($cityId, UpdateCityRequest $request)
    {
        return $this->city->update($cityId, $request);
    }

    public function destroy($cityId)
    {
        return $this->city->destroy($cityId);
    }
}
