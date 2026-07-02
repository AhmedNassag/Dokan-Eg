<?php

use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\PermissionController;
use App\Http\Controllers\Api\RoleController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Public auth routes
Route::post('/auth/register', [AuthController::class, 'register']);
Route::post('/auth/login', [AuthController::class, 'login'])->name('login');

// Protected routes
Route::middleware('auth:sanctum')->group(function () {
    Route::post('/auth/logout', [AuthController::class, 'logout']);
    Route::get('/user', function (Request $request) {
        return response()->json([
            'userData' => $request->user(),
            'userAbilityRules' => (new AuthController)->getUserAbilityRules($request->user()),
        ]);
    });

    // Category CRUD — permission-managed
    Route::get('category', [CategoryController::class, 'index']);
    Route::post('category', [CategoryController::class, 'store']);
    Route::get('category/{id}', [CategoryController::class, 'show']);
    Route::put('category/{id}', [CategoryController::class, 'update']);
    Route::delete('category/{id}', [CategoryController::class, 'destroy']);

    // Role CRUD — permission-managed
    Route::get('role', [RoleController::class, 'index']);
    Route::post('role', [RoleController::class, 'store']);
    Route::get('role/{id}', [RoleController::class, 'show']);
    Route::put('role/{id}', [RoleController::class, 'update']);
    Route::delete('role/{id}', [RoleController::class, 'destroy']);

    // Permission CRUD — permission-managed
    Route::get('permission', [PermissionController::class, 'index']);

    // User CRUD — permission-managed
    Route::get('user/list', [UserController::class, 'index']);
    Route::post('user', [UserController::class, 'store']);
    Route::get('user/{id}', [UserController::class, 'show']);
    Route::put('user/{id}', [UserController::class, 'update']);
    Route::delete('user/{id}', [UserController::class, 'destroy']);

    // Used by user form to populate role dropdown
    Route::get('roles-list', [UserController::class, 'roles']);

    // Country CRUD — permission-managed
    Route::get('country', [CountryController::class, 'index']);
    Route::post('country', [CountryController::class, 'store']);
    Route::get('country/{id}', [CountryController::class, 'show']);
    Route::put('country/{id}', [CountryController::class, 'update']);
    Route::delete('country/{id}', [CountryController::class, 'destroy']);

});