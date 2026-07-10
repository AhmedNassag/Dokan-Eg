<?php

namespace App\Repositories\Category;

use App\Http\Resources\Category\CategoryResource;
use App\Models\Category;
use App\Repositories\Category\CategoryInterface;
use Illuminate\Support\Facades\Auth;

class CategoryRepository implements CategoryInterface
{
    public function getModel()
    {
        return new Category();
    }

    public function index($request/*, $filter*/): \Illuminate\Http\JsonResponse
    {
        $user = Auth::user();

        $query = $this->getModel()->with('parent')
        // ->filter($filter)
        ;

        if (!$user->hasRole('admin')) {
            $query->where('created_by', $user->id);
        }

        $sortBy  = $request['sortBy'] ?? 'id';
        $orderBy = $request['orderBy'] ?? 'desc';
        $query->orderBy($sortBy, $orderBy);

        $itemsPerPage = $request['itemsPerPage'] ?? 10;
        $page         = $request['page'] ?? 1;
        $total        = $query->count();
        $categories   = $query->skip(($page - 1) * $itemsPerPage)->take($itemsPerPage)->get();

        return response()->json([
            'data'  => CategoryResource::collection($categories),
            'total' => $total,
        ]);
    }

    public function store($request)
    {
        $data = $request->validated();
        $data['created_by'] = Auth::id();
        $data['is_active']  = $request->boolean('is_active', true);

        $category = $this->getModel()->create($data)->load('parent');

        return response()->json(CategoryResource::make($category), 201);
    }

    public function show($categoryId)
    {
        $category = $this->getModel()->with('parent')->find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $user = Auth::user();
        if (!$user->hasRole('admin') && $category->created_by !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return response()->json(CategoryResource::make($category));
    }

    public function update($categoryId, $request)
    {
        $category = $this->getModel()->find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $user = Auth::user();
        if (!$user->hasRole('admin') && $category->created_by !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $data = $request->validated();

        if ($request->has('is_active')) {
            $data['is_active'] = $request->boolean('is_active');
        }

        $category->update($data);
        $category->load('parent');

        return response()->json(CategoryResource::make($category));
    }

    public function destroy($categoryId)
    {
        $category = $this->getModel()->find($categoryId);

        if (!$category) {
            return response()->json(['message' => 'Not Found'], 404);
        }

        $user = Auth::user();
        if (!$user->hasRole('admin') && $category->created_by !== $user->id) {
            return response()->json(['message' => 'Forbidden'], 403);
        }

        $category->delete();

        return response()->json(null, 204);
    }
}
