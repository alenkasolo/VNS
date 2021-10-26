<?php

namespace App\Services;

use App\Exceptions\EntityNotFoundException;
use App\Models\Role;
use App\Models\User;
use App\Repositories\IBaseRepository;
use App\Repositories\RoleRepository\IRoleRepository;
use App\Repositories\UserRepository\IUserRepository;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Validator;

abstract class BaseService implements IBaseService
{

    private IBaseRepository $repository;

    public function __construct(IBaseRepository $repository)
    {
        $this->repository = $repository;
    }

    public function getAll(): Collection
    {
        return $this->getRepository()->getAll();
    }

    /**
     * @throws EntityNotFoundException
     */
    public function get(mixed $id): Model
    {
        $model = $this->getRepository()->get($id);
        if (!$model) {
            throw new EntityNotFoundException(['entityName' => 'entity']);
        }
        return $model;
    }

    protected function baseCreate(array $attributes): Model|User|Role
    {
        return $this->getRepository()->create($attributes);
    }

    /**
     * @throws EntityNotFoundException
     */
    protected function baseUpdate(mixed $id, array $attributes): Model|User|Role
    {

        $updatedModel = $this->getRepository()->update($id, $attributes);
        if (!$updatedModel) {
            throw new EntityNotFoundException(['entityName' => 'entity']);
        }
        return $updatedModel;
    }

    function delete(mixed $id): int
    {
        return $this->getRepository()->delete($id);
    }


    /*
    check whether the queryString's value is in the whitelist
    -> yes: return its value
    -> otherwise: modify its value
    */
    protected function filterQueryString(string $queryString, array $whiteList, $defaultIndex = 0): mixed {
        if (!in_array(request()->input($queryString), $whiteList, true)) {
            $this->modifyInput($queryString, $whiteList, $defaultIndex);
        }
        return request()->input($queryString);
    }

    private function modifyInput(string $queryString, array $whiteList, int $defaultIndex): void {
        request()->query->add([$queryString => $whiteList[$defaultIndex]]);
    }

    /**
     * @return IUserRepository|IRoleRepository|IBaseRepository
     */
    protected function getRepository(): IBaseRepository|IUserRepository|IRoleRepository
    {
        return $this->repository;
    }

    protected function getUserStatusQueryStringWhiteList(): array {
        return [1, 0];
    }

    protected function getOrderQueryStringWhiteList(): array
    {
        return ['asc', 'desc'];
    }

    protected function getUserSortByQueryStringWhiteList(): array
    {
        return ['id', 'user_name', 'created_at', 'updated_at'];
    }

    protected function getRoleIdQueryStringWhiteList(): array
    {
        return [1, 2, 3, 4, 5];
    }

    protected function validate(Request $request,array $rules, $callback = null): array
    {
        $validator = Validator::make($request->all(), $rules);
        if ($callback) {
            $validator->after($callback);
        }
        if ($validator->fails()) {
            throw new ValidationException($validator);
        }
        return $validator->validated();
    }
}
