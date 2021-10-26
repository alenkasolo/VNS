<?php

namespace App\Services\RoleService;

use App\Models\Role;
use App\Services\BaseService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class RoleService extends BaseService implements IRoleService
{

    public function create(Request $request): Role
    {
        throw new \Exception();
    }

    public function update(mixed $id, Request $request): Role
    {
        throw new \Exception();
    }

}
