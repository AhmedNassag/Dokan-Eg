<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Unit\UnitFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Unit\StoreUnitRequest;
use App\Http\Requests\Unit\UpdateUnitRequest;
use App\Repositories\Unit\UnitInterface;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\Middleware;

class UnitController extends Controller
{
    protected $unit;

    public function __construct(UnitInterface $unit)
    {
        $this->unit = $unit;
    }

    public static function middleware(): array
    {
        return [
            'auth:sanctum',

            new Middleware('permission:list-units', only: ['index']),
            new Middleware('permission:store-units', only: ['store']),
            new Middleware('permission:show-units', only: ['show']),
            new Middleware('permission:update-units', only: ['update']),
            new Middleware('permission:destroy-units', only: ['destroy']),
        ];
    }

    public function index(Request $request, UnitFilter $filter)
    {
        return $this->unit->index($request, $filter);
    }

    public function show($unitId)
    {
        return $this->unit->show($unitId);
    }

    public function store(StoreUnitRequest $request)
    {
        return $this->unit->store($request);
    }

    public function update($unitId, UpdateUnitRequest $request)
    {
        return $this->unit->update($unitId, $request);
    }

    public function destroy($unitId)
    {
        return $this->unit->destroy($unitId);
    }
}
