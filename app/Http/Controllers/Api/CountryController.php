<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Country\CountryFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Country\StoreRequest;
use App\Http\Requests\Country\UpdateRequest;
use App\Repositories\Country\CountryInterface;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $country;

    public function __construct(CountryInterface $country)
    {
        $this->country = $country;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-country', ['only' => ['index']]);
        $this->middleware('permission:store-country', ['only' => ['store']]);
        $this->middleware('permission:show-country', ['only' => ['show']]);
        $this->middleware('permission:update-country', ['only' => ['update']]);
        $this->middleware('permission:destroy-country', ['only' => ['destroy']]);
    }

    public function index(Request $request, CountryFilter $filter)
    {
        return $this->country->index($request, $filter);
    }

    public function show($countryId)
    {
        return $this->country->show($countryId);
    }

    public function store(StoreRequest $request)
    {
        return $this->country->store($request);
    }

    public function update($countryId, UpdateRequest $request)
    {
        return $this->country->update($countryId, $request);
    }

    public function destroy($countryId)
    {
        return $this->country->destroy($countryId);
    }


}
