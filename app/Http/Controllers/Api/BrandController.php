<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Brand\BrandFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Brand\StoreBrandRequest;
use App\Http\Requests\Brand\UpdateBrandRequest;
use App\Repositories\Brand\BrandInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class BrandController extends Controller
{
    protected $brand;

    public function __construct(BrandInterface $brand)
    {
        $this->brand = $brand;
    }

    public static function middleware(): array
    {
        return [
            'auth:sanctum',

            new Middleware('permission:list-brands', only: ['index']),
            new Middleware('permission:store-brands', only: ['store']),
            new Middleware('permission:show-brands', only: ['show']),
            new Middleware('permission:update-brands', only: ['update']),
            new Middleware('permission:destroy-brands', only: ['destroy']),
        ];
    }

    public function index(Request $request, BrandFilter $filter)
    {
        return $this->brand->index($request, $filter);
    }

    public function show($brandId)
    {
        return $this->brand->show($brandId);
    }

    public function store(StoreBrandRequest $request)
    {
        return $this->brand->store($request);
    }

    public function update($brandId, UpdateBrandRequest $request)
    {
        return $this->brand->update($brandId, $request);
    }

    public function destroy($brandId)
    {
        return $this->brand->destroy($brandId);
    }
}
