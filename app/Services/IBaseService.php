<?php

namespace App\Services;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;

interface IBaseService
{
    public function getAll(): Collection;
    public function get(mixed $id): Model;
    public function create(Request $request): Model;
    public function update(mixed $id, Request $request): Model;
    function delete(mixed $id): int;
}
