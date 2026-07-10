<?php

namespace App\Repositories\Permission;

use App\Http\Resources\Permission\PermissionResource;
use App\Repositories\Permission\PermissionInterface;
use Spatie\Permission\Models\Permission;

class PermissionRepository implements PermissionInterface
{
    public function getModel()
    {
        return new Permission();
    }

    public function index($request/*, $filter*/): \Illuminate\Http\JsonResponse
    {
        $query = $this->getModel()
            // ->ordering($request->ordering)
            // ->filter($filter)
            ;

        $sortBy  = $request['sortBy'] ?? 'id';
        $orderBy = $request['orderBy'] ?? 'asc';
        $query->orderBy($sortBy, $orderBy);

        $itemsPerPage = $request['itemsPerPage'] ?? 10;
        $page         = $request['page'] ?? 1;

        $total = $query->count();
        $permissions = $query->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        return response()->json([
            'data'  => PermissionResource::collection($permissions),
            'total' => $total,
        ]);
    }
}
