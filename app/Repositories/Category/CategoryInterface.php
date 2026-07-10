<?php

namespace App\Repositories\Category;

interface CategoryInterface
{
    public function index($request, $filter);

    public function show($id);

    public function store($request);

    public function update($id, $request);

    public function destroy($id);
}
