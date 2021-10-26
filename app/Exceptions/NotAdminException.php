<?php

namespace App\Exceptions;

use JetBrains\PhpStorm\Pure;
use Throwable;

class NotAdminException extends CustomException
{
    #[Pure] public function __construct(array $attributes = [], $message = "You are not admin.", $code = 403, int $customCode = null, Throwable $previous = null)
    {
        $this->redirectToRoute = 'login';
        parent::__construct($attributes, $message, $code, $customCode, $previous);
    }

    protected function getLangMsg(array $attributes): string
    {
        return trans('exception.notAdmin');
    }
}
