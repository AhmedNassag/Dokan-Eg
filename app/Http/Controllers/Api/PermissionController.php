<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Permission\PermissionFilter;
use App\Http\Controllers\Controller;
use App\Repositories\Permission\PermissionInterface;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    protected $permission;

    public function __construct(PermissionInterface $permission)
    {
        $this->permission = $permission;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-permission', ['only' => ['index']]);
    }


    
    public function index(Request $request/*, PermissionFilter $filter*/)
    {
        return $this->permission->index($request/*, $filter*/);
    }
}
