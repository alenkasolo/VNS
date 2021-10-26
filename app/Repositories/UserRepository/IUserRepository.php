<?php

namespace App\Repositories\UserRepository;

use App\Repositories\ManyCreatable\IManyCreatable;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;

interface IUserRepository
{
    public function search(?string $keyWord, int $roleId, int $status, string $sortBy, string $order): LengthAwarePaginator;
    public function createMany(IManyCreatable $manyCreatable, Collection $data): array;
    public function loginViaEmail(string $email, string $password): ?User;
    public function loginViaUserName(string $userName, string $password): ?User;
}
