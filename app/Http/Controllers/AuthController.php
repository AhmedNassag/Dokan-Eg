<?php

namespace App\Http\Controllers;

use App\Enums\UserType;
use App\Enums\Status;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $request->validate([
            'name'      => ['required', 'string', 'max:255'],
            'email'     => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password'  => ['required', 'string', 'min:8', 'confirmed'],
            'user_type' => ['required', 'in:merchant,marketer'],
        ]);

        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'user_type' => $request->user_type,
            'status'    => Status::PENDING,
        ]);

        $user->assignRole($request->user_type);

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'accessToken'      => $token,
            'userData'         => $user,
            'userAbilityRules' => $this->getUserAbilityRules($user),
        ]);
    }


    
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => ['required', 'email'],
            'password' => ['required'],
        ]);

        if (!Auth::attempt($credentials)) {
            throw ValidationException::withMessages([
                'email' => ['البيانات التي أدخلتها غير صحيحة'],
            ]);
        }

        $user = Auth::user();

        if ($user->status !== Status::APPROVED) {
            throw ValidationException::withMessages([
                'email' => ['حسابك لم يتم الموافقة عليه بعد'],
            ]);
        }

        $token = $user->createToken('auth-token')->plainTextToken;

        return response()->json([
            'accessToken'      => $token,
            'userData'         => $user,
            'userAbilityRules' => $this->getUserAbilityRules($user),
        ]);
    }



    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['message' => 'Logged out successfully']);
    }



    public function getUserAbilityRules(User $user)
    {
        if ($user->hasRole('admin')) {
            return [
                ['action' => 'manage', 'subject' => 'all'],
            ];
        }

        $permissions = $user->getAllPermissions()->map(function ($permission) {
            $parts = explode('-', $permission->name, 2);

            return [
                'action'  => $parts[0] ?? $permission->name,
                'subject' => $parts[1] ?? $permission->name,
            ];
        })->values()->toArray();

        // If no permissions, add default read access to dashboard
        if (empty($permissions)) {
            $permissions[] = [
                'action'  => 'read',
                'subject' => 'dashboard',
            ];
        }

        return $permissions;
    }
}
