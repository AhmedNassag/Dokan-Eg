<?php

namespace App\Http\Controllers\Api;

use App\Filters\Api\User\UserFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\User\StoreRequest;
use App\Http\Requests\User\UpdateRequest;
use App\Repositories\User\UserInterface;
use Illuminate\Http\Request;

class UserController extends Controller
{
    protected $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;

        $this->middleware('auth:sanctum');

        $this->middleware('permission:list-user', ['only' => ['index']]);
        $this->middleware('permission:show-user', ['only' => ['show']]);
        $this->middleware('permission:store-user', ['only' => ['store']]);
        $this->middleware('permission:update-user', ['only' => ['update']]);
        $this->middleware('permission:destroy-user', ['only' => ['destroy']]);
    }

    public function index(Request $request, UserFilter $filter)
    {
        return $this->user->index($request, $filter);
    }

    public function show($id)
    {
        return $this->user->show($id);
    }

    public function store(StoreRequest $request)
    {
        return $this->user->store($request);
    }

    public function update($id, UpdateRequest $request)
    {
        return $this->user->update($id, $request);
    }

    public function destroy($id)
    {
        return $this->user->destroy($id);
    }

    public function roles()
    {
        return $this->user->roles();
    }
}
