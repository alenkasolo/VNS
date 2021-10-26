<?php

namespace App\Repositories\RoleRepository;

use App\Repositories\BaseRepository;

class RoleRepository extends BaseRepository implements IRoleRepository
{

    protected array $relations = ['users'];
}
