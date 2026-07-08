<?php

namespace App\Repositories\Brand;

interface BrandInterface
{
    public function index($request, $filter);
    public function show($brandId);
    public function store($request);
    public function update($brandId, $request);
    public function destroy($brandId);
}
