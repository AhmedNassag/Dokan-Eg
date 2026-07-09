<?php

namespace App\Repositories\City;

interface CityInterface
{
    public function index($request, $filter);
    
    public function show($id);
    
    public function store($request);
    
    public function update($id, $request);
    
    public function destroy($id);
    
}
