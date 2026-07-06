<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Area\AreaFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Area\StoreAreaRequest;
use App\Http\Requests\Area\UpdateAreaRequest;
use App\Repositories\Area\AreaInterface;
use Illuminate\Http\Request;

class AreaController extends Controller
{
    protected $area;

    public function __construct(AreaInterface $area)
    {
        $this->area = $area;
    }

    public function index(Request $request, AreaFilter $filter)
    {
        return $this->area->index($request, $filter);
    }

    public function show($areaId)
    {
        return $this->area->show($areaId);
    }

    public function store(StoreAreaRequest $request)
    {
        return $this->area->store($request);
    }

    public function update($areaId, UpdateAreaRequest $request)
    {
        return $this->area->update($areaId, $request);
    }

    public function destroy($areaId)
    {
        return $this->area->destroy($areaId);
    }
}
