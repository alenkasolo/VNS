<?php

namespace App\Providers;

use App\Repositories\ManyCreatable\IManyCreatable;
use App\Repositories\ManyCreatable\ManyCreatable;
use App\Repositories\RoleRepository\IRoleRepository;
use App\Repositories\RoleRepository\RoleRepository;
use App\Repositories\UserRepository\IUserRepository;
use App\Repositories\UserRepository\UserRepository;
use Illuminate\Support\ServiceProvider;

class RepositoryProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind(IManyCreatable::class, ManyCreatable::class);
        $this->app->bind(IUserRepository::class, UserRepository::class);
        $this->app->bind(IRoleRepository::class, RoleRepository::class);
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
