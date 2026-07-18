<?php

namespace App\Repositories\Shop;

interface ShopInterface
{
    public function index($request);

    public function show($id);

    public function store($request);

    public function update($id, $request);

    public function destroy($id);
}
