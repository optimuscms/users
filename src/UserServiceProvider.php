<?php

namespace Optimus\Users;

use Optimus\Users\Models\AdminUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    protected $controllerNamespace = 'Optimus\Users\Http\Controllers';

    public function boot()
    {
        // Migrations
        $this->loadMigrationsFrom(
            __DIR__ . '/../database/migrations'
        );

        // Auth
        $this->registerAdminGuard();

        // Routes
        $this->registerAdminRoutes();
    }

    protected function registerAdminGuard()
    {
        $this->app['config']->set('auth.guards.admin', [
            'driver' => 'session',
            'provider' => 'admins'
        ]);

        $this->app['config']->set('auth.providers.admins', [
            'driver' => 'eloquent',
            'model' => AdminUser::class
        ]);
    }

    protected function registerAdminRoutes()
    {
        Route::name('admin.')
             ->prefix('admin')
             ->middleware('web', 'auth:admin')
             ->namespace($this->controllerNamespace)
             ->group(function () {
                 Route::apiResource('users', 'AdminUsersController');
                 Route::get('user', 'AdminUsersController@show')->name(
                     'users.authenticated'
                 );
             });
    }
}
