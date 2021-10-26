<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\Pure;
use Throwable;

class TooManyAttemptsException extends CustomException
{
    #[Pure]
    public function __construct(array $attributes = [], $message = "Too many attempts", $code = 429, int $customCode = null, Throwable $previous = null)
    {
        parent::__construct($attributes, $message, $code, $customCode, $previous);
    }

    protected function getLangMsg(array $attributes): string
    {
        return trans('exception.tooManyAttempts');
    }
}
