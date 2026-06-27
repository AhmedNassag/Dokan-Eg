<?php

namespace App\Http\Controllers\Api;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;

class CategoryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
        
        $this->middleware('permission:list-category', ['only' => ['index']]);
        $this->middleware('permission:store-category', ['only' => ['store']]);
        $this->middleware('permission:show-category', ['only' => ['show']]);
        $this->middleware('permission:update-category', ['only' => ['update']]);
        $this->middleware('permission:destroy-category', ['only' => ['destroy']]);
    
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        $query = Category::query();

        if (!$user->hasRole('admin')) {
            $query->where('created_by', $user->id);
        }

        if ($search = $request->q) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $sortBy = $request->sortBy ?? 'id';
        $orderBy = $request->orderBy ?? 'desc';
        $query->orderBy($sortBy, $orderBy);

        $itemsPerPage = $request->itemsPerPage ?? 10;
        $page = $request->page ?? 1;

        $total = $query->count();
        $categories = $query->skip(($page - 1) * $itemsPerPage)
            ->take($itemsPerPage)
            ->get();

        return response()->json([
            'data' => $categories,
            'total' => $total,
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $data['created_by'] = Auth::id();

        $category = Category::create($data);

        return response()->json($category, 201);
    }

    public function show($id)
    {
        $category = Category::findOrFail($id);
        // $this->authorizeAccess($category);

        return response()->json($category);
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        // $this->authorizeAccess($category);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($data);

        return response()->json($category);
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        // $this->authorizeAccess($category);

        $category->delete();

        return response()->json(null, 204);
    }

    private function authorizeAccess(Category $category)
    {
        $user = Auth::user();

        if (!$user->hasRole('admin') && $category->created_by !== $user->id) {
            abort(403, 'Forbidden');
        }
    }
}
