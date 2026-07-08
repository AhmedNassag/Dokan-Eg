<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\ShippingCompany\ShippingCompanyFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingCompany\StoreShippingCompanyRequest;
use App\Http\Requests\ShippingCompany\UpdateShippingCompanyRequest;
use App\Repositories\ShippingCompany\ShippingCompanyInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class ShippingCompanyController extends Controller
{
    protected $shippingCompany;

    public function __construct(ShippingCompanyInterface $shippingCompany)
    {
        $this->shippingCompany = $shippingCompany;
    }
    public static function middleware(): array
    {
        return [
            'auth:sanctum',

            new Middleware('permission:list-shipping-company', only: ['index']),
            new Middleware('permission:store-shipping-company', only: ['store']),
            new Middleware('permission:show-shipping-company', only: ['show']),
            new Middleware('permission:update-shipping-company', only: ['update']),
            new Middleware('permission:destroy-shipping-company', only: ['destroy']),
        ];
    }

    public function index(Request $request, ShippingCompanyFilter $filter)
    {
        return $this->shippingCompany->index($request, $filter);
    }

    public function show($id)
    {
        return $this->shippingCompany->show($id);
    }

    public function store(StoreShippingCompanyRequest $request)
    {
        return $this->shippingCompany->store($request);
    }

    public function update($id, UpdateShippingCompanyRequest $request)
    {
        return $this->shippingCompany->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->shippingCompany->destroy($id);
    }
}