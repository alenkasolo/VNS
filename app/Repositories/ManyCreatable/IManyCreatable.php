<?php

namespace App\Repositories\ManyCreatable;

interface IManyCreatable {
    public function createMany(array $data, string $tableName): array;
}
