<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Shop\StoreRequest;
use App\Http\Requests\Shop\UpdateRequest;
use App\Repositories\Shop\ShopInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ShopController extends Controller
{
    protected $shop;

    public function __construct(ShopInterface $shop)
    {
        $this->shop = $shop;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-shop', ['only' => ['index']]);
        $this->middleware('permission:show-shop', ['only' => ['show']]);
        $this->middleware('permission:store-shop', ['only' => ['store']]);
        $this->middleware('permission:update-shop', ['only' => ['update']]);
        $this->middleware('permission:destroy-shop', ['only' => ['destroy']]);
    }



    public function index(Request $request)
    {
        return $this->shop->index($request);
    }



    public function show($id)
    {
        return $this->shop->show($id);
    }



    public function store(StoreRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();

        // Non-admins can only create a shop for themselves.
        if (!$user->hasRole('admin') || empty($data['user_id'])) {
            $data['user_id'] = $user->id;
        }

        // Only admins may set the featured flag.
        if (!$user->hasRole('admin')) {
            unset($data['is_featured']);
        }

        if ($request->hasFile('logo')) {
            $data['logo'] = $request->file('logo')->store('shops', 'public');
            $data['logo'] = Storage::url($data['logo']);
        }

        if ($request->hasFile('cover')) {
            $data['cover'] = $request->file('cover')->store('shops', 'public');
            $data['cover'] = Storage::url($data['cover']);
        }

        return $this->shop->store($data);
    }



    public function update($id, UpdateRequest $request)
    {
        $data = $request->validated();

        $user = $request->user();

        // Non-admins may not reassign ownership or featured flag.
        if (!$user->hasRole('admin')) {
            unset($data['user_id'], $data['is_featured']);
        }

        if ($request->hasFile('logo')) {
            $data['logo'] = Storage::url($request->file('logo')->store('shops', 'public'));
        }

        if ($request->hasFile('cover')) {
            $data['cover'] = Storage::url($request->file('cover')->store('shops', 'public'));
        }

        return $this->shop->update($id, $data);
    }



    public function destroy($id)
    {
        return $this->shop->destroy($id);
    }
}
