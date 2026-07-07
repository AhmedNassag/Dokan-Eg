<?php

namespace App\Repositories\Area;

interface AreaInterface
{
    public function index($request, $filter);
    public function show($areaId);
    public function store($request);
    public function update($areaId, $request);
    public function destroy($areaId);
}
