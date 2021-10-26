<?php

namespace App\Exceptions;
use JetBrains\PhpStorm\Pure;
use Throwable;

class EntityNotFoundException extends CustomException
{

    #[Pure] public function __construct(array $attributes = [], $message = "Entity not found.", $code = 404, int $customCode = 1, Throwable $previous = null)
    {
        parent::__construct($attributes, $message, $code, $customCode, $previous);
    }

    protected function getLangMsg(array $attributes): string
    {
        return trans('exception.entityNotFound', $attributes);
    }
}
