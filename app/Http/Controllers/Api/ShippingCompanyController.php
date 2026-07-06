<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\ShippingCompany\ShippingCompanyFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\ShippingCompany\StoreShippingCompanyRequest;
use App\Http\Requests\ShippingCompany\UpdateShippingCompanyRequest;
use App\Repositories\ShippingCompany\ShippingCompanyInterface;
use Illuminate\Http\Request;

class ShippingCompanyController extends Controller
{
    protected $shippingCompany;

    public function __construct(ShippingCompanyInterface $shippingCompany)
    {
        $this->shippingCompany = $shippingCompany;
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
