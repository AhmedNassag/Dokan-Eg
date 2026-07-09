<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Branch\BranchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Branch\StoreRequest;
use App\Http\Requests\Branch\UpdateRequest;
use App\Repositories\Branch\BranchInterface;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    protected $branch;

    public function __construct(BranchInterface $branch)
    {
        $this->branch = $branch;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-branch', ['only' => ['index']]);
        $this->middleware('permission:show-branch', ['only' => ['show']]);
        $this->middleware('permission:store-branch', ['only' => ['store']]);
        $this->middleware('permission:update-branch', ['only' => ['update']]);
        $this->middleware('permission:destroy-branch', ['only' => ['destroy']]);
    }




    public function index(Request $request, BranchFilter $filter)
    {
        return $this->branch->index($request, $filter);
    }

    

    public function show($branchId)
    {
        return $this->branch->show($branchId);
    }



    public function store(StoreRequest $request)
    {
        return $this->branch->store($request);
    }



    public function update($branchId, UpdateRequest $request)
    {
        return $this->branch->update($branchId, $request);
    }



    public function destroy($branchId)
    {
        return $this->branch->destroy($branchId);
    }
}
