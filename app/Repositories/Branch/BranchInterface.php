<?php

namespace App\Repositories\Branch;

interface BranchInterface
{
    public function index($request, $filter);
    public function show($branchId);
    public function store($request);
    public function update($branchId, $request);
    public function destroy($branchId);
}
