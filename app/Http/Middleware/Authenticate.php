<?php

namespace App\Http\Middleware;

use App\Exceptions\CustomAuthenticationException;
use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            return route('login');
        }
    }

    /**
     * @throws CustomAuthenticationException
     */
    protected function unauthenticated($request, array $guards)
    {
        throw new CustomAuthenticationException();
    }


}
