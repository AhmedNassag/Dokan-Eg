<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Area\AreaFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Area\StoreRequest;
use App\Http\Requests\Area\UpdateRequest;
use App\Repositories\Area\AreaInterface;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    protected $area;

    public function __construct(AreaInterface $area)
    {
        $this->area = $area;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-area', ['only' => ['index']]);
        $this->middleware('permission:show-area', ['only' => ['show']]);
        $this->middleware('permission:store-area', ['only' => ['store']]);
        $this->middleware('permission:update-area', ['only' => ['update']]);
        $this->middleware('permission:destroy-area', ['only' => ['destroy']]);
    }



    public function index(Request $request, AreaFilter $filter)
    {
        return $this->area->index($request, $filter);
    }



    public function show($areaId)
    {
        return $this->area->show($areaId);
    }



    public function store(StoreRequest $request)
    {
        return $this->area->store($request);
    }



    public function update($areaId, UpdateRequest $request)
    {
        return $this->area->update($areaId, $request);
    }



    public function destroy($areaId)
    {
        return $this->area->destroy($areaId);
    }
}
