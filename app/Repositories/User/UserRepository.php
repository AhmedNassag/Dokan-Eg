<?php

namespace App\Repositories\User;

use App\Http\Resources\User\UserResource;
use App\Models\User;
use App\Repositories\User\UserInterface;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserRepository implements UserInterface
{
    public function getModel()
    {
        return new User();
    }

    public function index($request/*, $filter*/): \Illuminate\Http\JsonResponse
    {
        $query = $this->getModel()->with('roles')
            // ->filter($filter)
            ;

        $sortBy  = $request['sortBy'] ?? 'id';
        $orderBy = $request['orderBy'] ?? 'asc';
        $query->orderBy($sortBy, $orderBy);

        $itemsPerPage = $request['itemsPerPage'] ?? 10;
        $page         = $request['page'] ?? 1;

        $total = $query->count();
        $users = $query->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        return response()->json([
            'data'  => UserResource::collection($users),
            'total' => $total,
        ]);
    }

    public function show($userId)
    {
        $user = $this->getModel()->with('roles')->find($userId);

        if (!$user) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        return response()->json(UserResource::make($user));
    }

    public function store($request)
    {
        $data = $request->validated();
        $data['password'] = Hash::make($data['password']);

        $user = $this->getModel()->create($data);

        $role = Role::where('id', $data['role'])->where('guard_name', 'web')->first();
        $user->assignRole($role->id);

        $user->load('roles');

        return response()->json(UserResource::make($user), 201);
    }

    public function update($userId, $request)
    {
        $user = $this->getModel()->find($userId);

        if (!$user) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $data = $request->validated();

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        if ($request->has('role')) {
            $role = Role::where('id', $data['role'])->where('guard_name', 'web')->first();
            $user->syncRoles([$role->id]);
        }

        $user->load('roles');

        return response()->json(UserResource::make($user));
    }

    public function destroy($userId)
    {
        $user = $this->getModel()->find($userId);

        if (!$user) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        if ($user->hasRole('admin') && $user->email === 'admin@demo.com') {
            return response()->json(['message' => 'Cannot delete the super admin user'], 403);
        }

        $user->delete();

        return response()->json(null, 204);
    }

    public function roles()
    {
        $roles = Role::all();
        return response()->json($roles);
    }
}
