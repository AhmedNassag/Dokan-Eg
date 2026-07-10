<?php

namespace App\Repositories\User;

interface UserInterface
{
    public function index($request, $filter);

    public function show($id);

    public function store($request);

    public function update($id, $request);

    public function destroy($id);
    
    public function roles();
}
