<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-permission', ['only' => ['index']]);
        $this->middleware('permission:store-permission', ['only' => ['store']]);
        $this->middleware('permission:show-permission', ['only' => ['show']]);
        $this->middleware('permission:update-permission', ['only' => ['update']]);
        $this->middleware('permission:destroy-permission', ['only' => ['destroy']]);
    }

    public function index(Request $request)
    {
        $query = Permission::query();

        if ($search = $request->q) {
            $query->where('name', 'like', "%{$search}%");
        }

        $sortBy = $request->sortBy ?? 'id';
        $orderBy = $request->orderBy ?? 'asc';
        $query->orderBy($sortBy, $orderBy);

        $itemsPerPage = $request->itemsPerPage ?? 10;
        $page = $request->page ?? 1;

        $total = $query->count();
        $permissions = $query->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        return response()->json([
            'data' => $permissions,
            'total' => $total,
        ]);
    }
}
