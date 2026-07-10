<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\City\CityFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\City\StoreRequest;
use App\Http\Requests\City\UpdateRequest;
use App\Repositories\City\CityInterface;
use Illuminate\Http\Request;

class CityController extends Controller
{
    protected $city;

    public function __construct(CityInterface $city)
    {
        $this->city = $city;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-city', ['only' => ['index']]);
        $this->middleware('permission:show-city', ['only' => ['show']]);
        $this->middleware('permission:store-city', ['only' => ['store']]);
        $this->middleware('permission:update-city', ['only' => ['update']]);
        $this->middleware('permission:destroy-city', ['only' => ['destroy']]);
    }



    public function index(Request $request/*, CityFilter $filter*/)
    {
        return $this->city->index($request/*, $filter*/);
    }



    public function show($id)
    {
        return $this->city->show($id);
    }



    public function store(StoreRequest $request)
    {
        return $this->city->store($request);
    }



    public function update($id, UpdateRequest $request)
    {
        return $this->city->update($id, $request);
    }



    public function destroy($id)
    {
        return $this->city->destroy($id);
    }
}
