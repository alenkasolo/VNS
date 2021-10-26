<?php

namespace App\Providers;

use App\Services\RoleService\IRoleService;
use App\Services\RoleService\RoleService;
use App\Services\UserService\IUserService;
use App\Services\UserService\UserService;
use Illuminate\Support\ServiceProvider;

class ControllerServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IUserService::class, UserService::class);
        $this->app->bind(IRoleService::class, RoleService::class);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
