<?php

namespace App\Repositories\Country;

interface CountryInterface
{
    // public function get();
    public function index($request, $filter);
    public function show($countryId);

    public function store($request);

    public function update($countryId,$request);

    public function destroy($countryId);
}
