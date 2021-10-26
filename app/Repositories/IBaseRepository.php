<?php

namespace App\Repositories;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{
    public function getAll(): Collection;
    public function get(mixed $id): ?Model;
    public function create(array $attributes): Model;
    public function update(mixed $id, array $attributes): ?Model;
    public function delete(mixed $id): int;

}


