<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\ShippingCompany\ShippingCompanyFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingCompany\StoreRequest;
use App\Http\Requests\ShippingCompany\UpdateRequest;
use App\Repositories\ShippingCompany\ShippingCompanyInterface;
use Illuminate\Http\Request;

class ShippingCompanyController extends Controller
{
    protected $shippingCompany;

    public function __construct(ShippingCompanyInterface $shippingCompany)
    {
        $this->shippingCompany = $shippingCompany;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-shippingCompany', ['only' => ['index']]);
        $this->middleware('permission:show-shippingCompany', ['only' => ['show']]);
        $this->middleware('permission:store-shippingCompany', ['only' => ['store']]);
        $this->middleware('permission:update-shippingCompany', ['only' => ['update']]);
        $this->middleware('permission:destroy-shippingCompany', ['only' => ['destroy']]);
    }



    public function index(Request $request, ShippingCompanyFilter $filter)
    {
        return $this->shippingCompany->index($request, $filter);
    }



    public function show($id)
    {
        return $this->shippingCompany->show($id);
    }



    public function store(StoreRequest $request)
    {
        return $this->shippingCompany->store($request);
    }



    public function update($id, UpdateRequest $request)
    {
        return $this->shippingCompany->update($id, $request);
    }


    
    public function destroy($id)
    {
        return $this->shippingCompany->destroy($id);
    }
}
