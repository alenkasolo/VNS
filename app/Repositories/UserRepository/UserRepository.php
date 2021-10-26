<?php

namespace App\Repositories\UserRepository;

use App\Repositories\BaseRepository;
use App\Repositories\ManyCreatable\IManyCreatable;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use JetBrains\PhpStorm\Pure;

class UserRepository extends BaseRepository implements IUserRepository
{

    protected array $relations = ['role'];

    #[Pure] public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function getAll(): Collection
    {
        return $this->baseGetAll($this->relations());
    }

    public function get(mixed $id): ?Model
    {
        return $this->baseGet($id, $this->relations());
    }

    public function search(?string $keyWord, int $roleId, int $status, string $sortBy, string $order): LengthAwarePaginator
    {
        $builder = $this->getModel()->whereRoleId($roleId)->whereStatus($status)->orderBy($sortBy, $order)->with('role');
        if ($keyWord) {
            $builder->where('user_name', 'ILIKE', '%'.$keyWord.'%');
        }
        return $builder->paginate();
    }

    public function update(mixed $id, array $attributes): ?Model
    {
        return $this->baseUpdate($id, $attributes, $this->relations());
    }


    public function createMany(IManyCreatable $manyCreatable, Collection $data): array
    {
        $result = $manyCreatable->createMany($data->toArray(), 'users');
        $data->load($this->relations());
        return $result;
    }

    public function loginViaEmail(string $email, string $password): ?User
    {
        return $this->getModel()->whereEmail($email)->wherePassword($password)->first();
    }

    public function loginViaUserName(string $userName, string $password): ?User
    {
        return $this->getModel()->whereUserName($userName)->wherePassword($password)->first();
    }

}
