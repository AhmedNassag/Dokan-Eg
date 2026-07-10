<?php

namespace App\Repositories\Role;

use App\Http\Resources\Role\RoleResource;
use App\Repositories\Role\RoleInterface;
use Spatie\Permission\Models\Role;

class RoleRepository implements RoleInterface
{
    public function getModel()
    {
        return new Role();
    }

    public function index($request/*, $filter*/): \Illuminate\Http\JsonResponse
    {

        $query = $this->getModel()->with('permissions')
            // ->ordering($request->ordering)
            // ->filter($filter)
            ;

        $sortBy  = $request['sortBy'] ?? 'id';
        $orderBy = $request['orderBy'] ?? 'asc';
        $query->orderBy($sortBy, $orderBy);

        $itemsPerPage = $request['itemsPerPage'] ?? 10;
        $page         = $request['page'] ?? 1;

        $total = $query->count();
        $roles = $query->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        return response()->json([
            'data'  => RoleResource::collection($roles),
            'total' => $total,
        ]);
    }

    public function show($id)
    {
        $role = $this->getModel()->with('permissions')->find($id);

        if (!$role) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json(RoleResource::make($role));
    }

    public function store($request)
    {
        $data = $request->validated();
        $role = $this->getModel()->create(['name' => $data['name'], 'guard_name' => 'web']);

        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        $role->load('permissions');

        return response()->json(RoleResource::make($role), 201);
    }

    public function update($id, $request)
    {
        $role = $this->getModel()->find($id);

        if (!$role) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $data = $request->validated();
        $role->update(['name' => $data['name']]);

        if ($request->has('permissions')) {
            $role->syncPermissions($data['permissions'] ?? []);
        }

        $role->load('permissions');

        return response()->json(RoleResource::make($role));
    }

    public function destroy($id)
    {
        $role = $this->getModel()->find($id);

        if (!$role) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if (in_array($role->name, ['admin', 'merchant', 'marketer'])) {
            return response()->json(['message' => 'Cannot delete system roles'], 403);
        }

        $role->delete();

        return response()->json(null, 204);
    }
}
