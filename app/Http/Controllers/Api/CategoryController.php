<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\Category\CategoryFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\Category\StoreRequest;
use App\Http\Requests\Category\UpdateRequest;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $category;

    public function __construct(CategoryInterface $category)
    {
        $this->category = $category;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-category', ['only' => ['index']]);
        $this->middleware('permission:show-category', ['only' => ['show']]);
        $this->middleware('permission:store-category', ['only' => ['store']]);
        $this->middleware('permission:update-category', ['only' => ['update']]);
        $this->middleware('permission:destroy-category', ['only' => ['destroy']]);
    }



    public function index(Request $request, CategoryFilter $filter)
    {
        return $this->category->index($request, $filter);
    }



    public function show($id)
    {
        return $this->category->show($id);
    }



    public function store(StoreRequest $request)
    {
        return $this->category->store($request);
    }



    public function update($id, UpdateRequest $request)
    {
        return $this->category->update($id, $request);
    }

    

    public function destroy($id)
    {
        return $this->category->destroy($id);
    }
}
