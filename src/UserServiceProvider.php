<?php

namespace Optimus\Users;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    protected $controllerNamespace = 'Optimus\Users\Http\Controllers';

    public function boot()
    {
        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');

        // Routes
        $this->registerAdminRoutes();
    }

    protected function registerAdminRoutes()
    {
        Route::name('admin.')
             ->prefix('admin')
             ->middleware('web')
             ->namespace($this->controllerNamespace)
             ->group(function () {
                 // Users
                 Route::get('user', 'AdminUsersController@show');
                 Route::apiResource('users', 'AdminUsersController');
             });
    }
}
