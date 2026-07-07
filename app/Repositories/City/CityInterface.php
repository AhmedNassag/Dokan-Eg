<?php

namespace App\Repositories\City;

interface CityInterface
{
    public function index($request, $filter);
    public function show($cityId);
    public function store($request);
    public function update($cityId, $request);
    public function destroy($cityId);
}
