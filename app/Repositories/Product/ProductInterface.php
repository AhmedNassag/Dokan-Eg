<?php

namespace App\Repositories\Api\Product;

interface ProductInterface
{
    public function index($request, $filter);

    public function show($id);

    public function store($request);

    public function update($request,$id);

    public function destroy($id);

    public function adjustStock($id, $request);

    public function lowStock($request);
}
