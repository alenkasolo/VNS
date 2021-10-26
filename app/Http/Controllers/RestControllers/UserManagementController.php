<?php

namespace App\Http\Controllers\RestControllers;


use App\Http\Controllers\Controller;
use App\Http\Resources\RoleCollection;
use App\Http\Resources\UserCollection;
use App\Http\Resources\UserResource;
use App\Services\UserService\IUserService;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class UserManagementController extends Controller
{

    private IUserService $userService;

    public function __construct(IUserService $userService)
    {
        $this->setUserService($userService);
    }

    public function get(string $id): UserResource
    {
        $user = $this->getUserService()->get($id);
        return new UserResource($user);
    }

    public function search(): UserCollection {
        $paginatedUsers = $this->getUserService()->search();
        return new UserCollection($paginatedUsers);
    }

    public function getAllRoles(): RoleCollection {
        $roles = app()->call([$this->getUserService(), 'getAllRoles']);
        return new RoleCollection($roles);
    }

    public function create(Request $request): UserResource {
        $createdUser =$this->getUserService()->create($request);
        return new UserResource($createdUser);
    }

    public function createMany(Request $request): UserCollection {
        $newUsers = $this->getUserService()->createMany($request);
        return new UserCollection($newUsers);
    }

    public function update(Request $request, string $id): UserResource {
        $updatedUser = $this->getUserService()->update($id, $request);
        return new UserResource($updatedUser);
    }

    public function delete(string $id): Response {
        $this->getUserService()->delete($id);
        return response()->noContent();
    }

    protected function getUserService(): IUserService {
        return $this->userService;
    }

    protected function setUserService(IUserService $userService): void {
        $this->userService = $userService;
    }

}
