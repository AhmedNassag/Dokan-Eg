<?php

use App\Http\Controllers\Api\AreaController;
use App\Http\Controllers\Api\BranchController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\CityController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\LanguageController;
use App\Http\Controllers\Api\ShippingCompanyController;
use App\Http\Controllers\Api\TranslationController;
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

    // City CRUD — permission-managed
    Route::get('city', [CityController::class, 'index']);
    Route::post('city', [CityController::class, 'store']);
    Route::get('city/{id}', [CityController::class, 'show']);
    Route::put('city/{id}', [CityController::class, 'update']);
    Route::delete('city/{id}', [CityController::class, 'destroy']);

    // Area CRUD — permission-managed
    Route::get('area', [AreaController::class, 'index']);
    Route::post('area', [AreaController::class, 'store']);
    Route::get('area/{id}', [AreaController::class, 'show']);
    Route::put('area/{id}', [AreaController::class, 'update']);
    Route::delete('area/{id}', [AreaController::class, 'destroy']);

    // Branch CRUD — permission-managed
    Route::get('branch', [BranchController::class, 'index']);
    Route::post('branch', [BranchController::class, 'store']);
    Route::get('branch/{id}', [BranchController::class, 'show']);
    Route::put('branch/{id}', [BranchController::class, 'update']);
    Route::delete('branch/{id}', [BranchController::class, 'destroy']);

    // ShippingCompany CRUD — permission-managed
    Route::get('shippingCompany', [ShippingCompanyController::class, 'index']);
    Route::post('shippingCompany', [ShippingCompanyController::class, 'store']);
    Route::get('shippingCompany/{id}', [ShippingCompanyController::class, 'show']);
    Route::put('shippingCompany/{id}', [ShippingCompanyController::class, 'update']);
    Route::delete('shippingCompany/{id}', [ShippingCompanyController::class, 'destroy']);

    // Language CRUD — permission-managed
    Route::get('language', [LanguageController::class, 'index']);
    Route::post('language', [LanguageController::class, 'store']);
    Route::get('language/{id}', [LanguageController::class, 'show']);
    Route::put('language/{id}', [LanguageController::class, 'update']);
    Route::delete('language/{id}', [LanguageController::class, 'destroy']);

    // Translation CRUD — permission-managed
    Route::get('translation', [TranslationController::class, 'index']);
    Route::post('translation', [TranslationController::class, 'store']);
    Route::get('translation/{id}', [TranslationController::class, 'show']);
    Route::put('translation/{id}', [TranslationController::class, 'update']);
    Route::delete('translation/{id}', [TranslationController::class, 'destroy']);

    // Translation export (public for frontend i18n)
    Route::get('translations/export', [TranslationController::class, 'export']);
});
