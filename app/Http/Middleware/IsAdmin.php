<?php

namespace App\Http\Middleware;

use App\Exceptions\NotAdminException;
use App\Services\UserService\IUserService;
use Auth;
use Closure;
use Illuminate\Http\Request;

class IsAdmin
{
    private IUserService $userService;
    public function __construct(IUserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     * @throws NotAdminException
     */
    public function handle(Request $request, Closure $next): mixed {
        if (Auth::check() && $this->userService->get(Auth::id())->role->name === 'System Manager') {
            return $next($request);
        }
        throw new NotAdminException();
    }
}
