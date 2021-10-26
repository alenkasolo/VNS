<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\Pure;
use Throwable;

class CustomAuthenticationException extends CustomException
{
    protected ?string $redirectToRoute = 'home';
    #[Pure] public function __construct(array $attributes = [], $message = "You are not authenticated yet.", $code = 401, int $customCode = null, Throwable $previous = null)
    {
        parent::__construct($attributes, $message, $code, $customCode, $previous);
    }

    protected function getLangMsg(array $attributes): string
    {
        return trans('exception.unauthorized');
    }
}
