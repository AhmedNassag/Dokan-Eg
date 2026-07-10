<?php

namespace App\Repositories\Permission;

interface PermissionInterface
{
    public function index($request, $filter);
}
