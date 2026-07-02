<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Country\CountryFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Country\StoreCountryRequest;
use App\Http\Requests\Country\UpdateCountryRequest;
use App\Repositories\Country\CountryInterface;
use Illuminate\Http\Request;

class CountryController extends Controller
{
    protected $country;
    public function __construct(CountryInterface $country)
    {
        $this->country = $country;
    }
    // public function get()
    // {
    //     $countrys = $this->country->get();
    //     return $countrys;
    // }

    public function index(Request $request, CountryFilter $filter)
    {
        return $this->country->index($request, $filter);
    }

    public function show($countryId)
    {
        return $this->country->show($countryId);
    }

    public function store(StoreCountryRequest $request)
    {
        return $this->country->store($request);
    }

    public function update($countryId, UpdateCountryRequest $request)
    {
        return $this->country->update($countryId, $request);
    }

    public function destroy($countryId)
    {
        return $this->country->destroy($countryId);
    }


}