<?php

namespace App\Repositories;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

abstract class BaseRepository implements IBaseRepository
{
    private Model $model;
    protected array $relations = [];
    public function __construct(Model $model)
    {
        $this->model = $model;
    }

    protected function baseGetAll(array $relations = []): Collection
    {
        return $this->getModel()->with($relations)->get();
    }

    protected function baseGet(mixed $id, array $relations = []): ?Model
    {
        return $this->getModel()->whereId($id)->with($relations)->first();
    }

    public function getAll(): Collection
    {
        return $this->baseGetAll();
    }

    public function get(mixed $id): ?Model
    {
        return $this->baseGet($id);
    }

    public function create(array $attributes): Model
    {
        return $this->getModel()->create($attributes);
    }

    public function update(mixed $id, array $attributes): ?Model
    {
        return $this->baseUpdate($id, $attributes);
    }

    protected function baseUpdate(mixed $id, array $attributes, array $relations = []): ?Model
    {
        $model = $this->get($id);
        if ($model) {
            $model->update($attributes);
            $model = $model->load($relations);
        }
        return $model;
    }

    public function delete(mixed $id): int
    {
        return $this->getModel()->destroy($id);
    }

    /**
     * @return User|Role|Model
     */
    public function getModel(): Model|User|Role
    {
        return $this->model;
    }

    protected function relations(array $excludes = []): array {
        return array_diff($this->relations, $excludes);
    }
}
