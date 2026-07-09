<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\Validator;

class RoleController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-role', ['only' => ['index']]);
        $this->middleware('permission:show-role', ['only' => ['show']]);
        $this->middleware('permission:store-role', ['only' => ['store']]);
        $this->middleware('permission:update-role', ['only' => ['update']]);
        $this->middleware('permission:destroy-role', ['only' => ['destroy']]);
    }



    public function index(Request $request)
    {
        $query = Role::with('permissions');

        if ($search = $request->q) {
            $query->where('name', 'like', "%{$search}%");
        }

        $sortBy = $request->sortBy ?? 'id';
        $orderBy = $request->orderBy ?? 'asc';
        $query->orderBy($sortBy, $orderBy);

        $itemsPerPage = $request->itemsPerPage ?? 10;
        $page = $request->page ?? 1;

        $total = $query->count();
        $roles = $query->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        return response()->json([
            'data' => $roles,
            'total' => $total,
        ]);
    }



    public function show($id)
    {
        $role = Role::with('permissions')->findOrFail($id);

        return response()->json($role);
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name',
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create(['name' => $data['name'], 'guard_name' => 'web']);

        if (!empty($data['permissions'])) {
            $role->syncPermissions($data['permissions']);
        }

        $role->load('permissions');

        return response()->json($role, 201);
    }



    public function update(Request $request, $id)
    {
        $role = Role::findOrFail($id);

        $data = $request->validate([
            'name'          => 'required|string|max:255|unique:roles,name,' . $id,
            'permissions'   => 'nullable|array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $data['name']]);

        if ($request->has('permissions')) {
            $role->syncPermissions($data['permissions'] ?? []);
        }

        $role->load('permissions');

        return response()->json($role);
    }



    public function destroy($id)
    {
        $role = Role::findOrFail($id);

        if (in_array($role->name, ['admin', 'merchant', 'marketer'])) {
            return response()->json(['message' => 'Cannot delete system roles'], 403);
        }

        $role->delete();

        return response()->json(null, 204);
    }
}
