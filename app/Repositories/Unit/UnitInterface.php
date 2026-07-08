<?php

namespace App\Repositories\Unit;

interface UnitInterface
{
    public function index($request, $filter);
    public function show($unitId);
    public function store($request);
    public function update($unitId, $request);
    public function destroy($unitId);
}
