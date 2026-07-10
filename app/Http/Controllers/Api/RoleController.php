<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Role\RoleFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\StoreRequest;
use App\Http\Requests\Role\UpdateRequest;
use App\Repositories\Role\RoleInterface;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    protected $role;

    public function __construct(RoleInterface $role)
    {
        $this->role = $role;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-role', ['only' => ['index']]);
        $this->middleware('permission:show-role', ['only' => ['show']]);
        $this->middleware('permission:store-role', ['only' => ['store']]);
        $this->middleware('permission:update-role', ['only' => ['update']]);
        $this->middleware('permission:destroy-role', ['only' => ['destroy']]);
    }



    public function index(Request $request/*, RoleFilter $filter*/)
    {
        return $this->role->index($request/*, $filter*/);
    }



    public function show($id)
    {
        return $this->role->show($id);
    }



    public function store(StoreRequest $request)
    {
        return $this->role->store($request);
    }



    public function update($id, UpdateRequest $request)
    {
        return $this->role->update($id, $request);
    }


    
    public function destroy($id)
    {
        return $this->role->destroy($id);
    }
}
