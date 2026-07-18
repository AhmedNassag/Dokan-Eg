<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Shop;
use App\Models\ShopSection;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ShopSectionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:sanctum');
    }

    public function index(Request $request, int $shopId): JsonResponse
    {
        $shop = $this->getShop($request, $shopId);
        $sections = $shop->sections()->ordered()->get();

        return response()->json(['data' => $sections]);
    }

    public function store(Request $request, int $shopId): JsonResponse
    {
        $shop = $this->getShop($request, $shopId);

        $validated = $request->validate([
            'type'     => 'required|string|max:50',
            'title'    => 'nullable|string|max:255',
            'content'  => 'nullable|array',
            'settings' => 'nullable|array',
        ]);

        $maxPosition = $shop->sections()->max('position') ?? 0;
        $validated['shop_id']  = $shop->id;
        $validated['position'] = $maxPosition + 1;

        $section = ShopSection::create($validated);

        return response()->json(['data' => $section], 201);
    }

    public function update(Request $request, int $shopId, int $sectionId): JsonResponse
    {
        $shop = $this->getShop($request, $shopId);
        $section = $shop->sections()->findOrFail($sectionId);

        $validated = $request->validate([
            'title'    => 'nullable|string|max:255',
            'content'  => 'nullable|array',
            'settings' => 'nullable|array',
            'is_active' => 'nullable|boolean',
        ]);

        $section->update($validated);

        return response()->json(['data' => $section]);
    }

    public function destroy(int $shopId, int $sectionId): JsonResponse
    {
        $shop = Shop::withoutGlobalScopes()->findOrFail($shopId);
        $section = $shop->sections()->findOrFail($sectionId);
        $section->delete();

        return response()->json(['message' => 'Section deleted']);
    }

    public function reorder(Request $request, int $shopId): JsonResponse
    {
        $shop = Shop::withoutGlobalScopes()->findOrFail($shopId);

        $validated = $request->validate([
            'ids'   => 'required|array',
            'ids.*' => 'integer|exists:shop_sections,id',
        ]);

        foreach ($validated['ids'] as $position => $id) {
            $shop->sections()->where('id', $id)->update(['position' => $position]);
        }

        $sections = $shop->sections()->ordered()->get();

        return response()->json(['data' => $sections]);
    }

    private function getShop(Request $request, int $shopId): Shop
    {
        return Shop::withoutGlobalScopes()
            ->where('id', $shopId)
            ->where(function ($query) use ($request) {
                if (!$request->user()->hasRole('admin')) {
                    $query->where('user_id', $request->user()->id);
                }
            })
            ->firstOrFail();
    }
}
