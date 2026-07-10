<?php

namespace App\Repositories\Area;

interface AreaInterface
{
    public function index($request/*, $filter*/);
    
    public function show($id);
    
    public function store($request);
    
    public function update($id, $request);
    
    public function destroy($id);
}
