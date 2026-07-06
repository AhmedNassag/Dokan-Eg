<?php

namespace App\Repositories\ShippingCompany;

interface ShippingCompanyInterface
{
    public function index($request, $filter);
    public function show($id);
    public function store($request);
    public function update($id, $request);
    public function destroy($id);
}
