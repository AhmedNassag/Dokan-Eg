<?php

namespace App\Http\Controllers\Api;

use App\Enums\Status;
use App\Enums\UserType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Enum;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-user', ['only' => ['index']]);
        $this->middleware('permission:show-user', ['only' => ['show']]);
        $this->middleware('permission:store-user', ['only' => ['store']]);
        $this->middleware('permission:update-user', ['only' => ['update']]);
        $this->middleware('permission:destroy-user', ['only' => ['destroy']]);
    }



    public function index(Request $request)
    {
        $query = User::with('roles');

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $sortBy  = $request->sortBy ?? 'id';
        $orderBy = $request->orderBy ?? 'asc';
        $query->orderBy($sortBy, $orderBy);

        $itemsPerPage = $request->itemsPerPage ?? 10;
        $page         = $request->page ?? 1;

        $total = $query->count();
        $users = $query->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get()->map(function ($user) {
                $user->role_names = $user->getRoleNames();
                return $user;
            });

        return response()->json([
            'data'  => $users,
            'total' => $total,
        ]);
    }



    public function show($id)
    {
        $user = User::with('roles')->findOrFail($id);
        $user->role_names = $user->getRoleNames();

        return response()->json($user);
    }



    public function store(Request $request)
    {
        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users',
            'password'  => 'required|string|min:8',
            'user_type' => ['required', new Enum(UserType::class)],
            'status'    => ['required', new Enum(Status::class)],
            'role'      => 'required|exists:roles,id',
        ]);

        $data['password'] = Hash::make($data['password']);

        $user = User::create($data);

        // $role = Role::findById($data['role']);
        $role = Role::where('id', $data['role'])->where('guard_name', 'web')->first();
        $user->assignRole($role->id);

        $user->load('roles');
        $user->role_names = $user->getRoleNames();

        return response()->json($user, 201);
    }



    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $data = $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|string|email|max:255|unique:users,email,' . $id,
            'password'  => 'nullable|string|min:8',
            'user_type' => ['required', new Enum(UserType::class)],
            'status'    => ['required', new Enum(Status::class)],
            'role'      => 'sometimes|exists:roles,id',
        ]);

        if (empty($data['password'])) {
            unset($data['password']);
        } else {
            $data['password'] = Hash::make($data['password']);
        }

        $user->update($data);

        if ($request->has('role')) {
            // $role = Role::findById($data['role']);
            $role = Role::where('id', $data['role'])->where('guard_name', 'web')->first();
            $user->syncRoles([$role->id]);
        }

        $user->load('roles');
        $user->role_names = $user->getRoleNames();

        return response()->json($user);
    }



    public function destroy($id)
    {
        $user = User::findOrFail($id);

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
