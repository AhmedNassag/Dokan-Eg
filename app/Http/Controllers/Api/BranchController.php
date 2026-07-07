<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Branch\BranchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\StoreBranchRequest;
use App\Http\Requests\Branch\UpdateBranchRequest;
use App\Repositories\Branch\BranchInterface;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected $branch;

    public function __construct(BranchInterface $branch)
    {
        $this->branch = $branch;
    }

    public function index(Request $request, BranchFilter $filter)
    {
        return $this->branch->index($request, $filter);
    }

    public function show($branchId)
    {
        return $this->branch->show($branchId);
    }

    public function store(StoreBranchRequest $request)
    {
        return $this->branch->store($request);
    }

    public function update($branchId, UpdateBranchRequest $request)
    {
        return $this->branch->update($branchId, $request);
    }

    public function destroy($branchId)
    {
        return $this->branch->destroy($branchId);
    }
}
