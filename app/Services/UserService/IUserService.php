<?php

namespace App\Services\UserService;

use App\Services\IBaseService;
use App\Services\RoleService\IRoleService;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUserService extends IBaseService
{
    public function search(): LengthAwarePaginator;

    public function getAllRoles(IRoleService $roleService): Collection;

    public function login(Request $request): void;

    public function logout(Request $request): void;

    public function createMany(Request $request): Collection;

    public function forgotPassword(Request $request): void;

    public function resetPassword(Request $request): void;

}

