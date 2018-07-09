<?php

namespace Optimus\Users\Providers;

use Optimus\Users\AdminUser;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    protected $controllerNamespace = 'Optimus\Users\Http\Controllers';

    public function boot()
    {
        // Migrations
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        // Guards
        $this->registerAdminGuard();

        // Routes
        $this->registerAdminRoutes();
    }

    public function register()
    {
        $this->app->register(LaravelServiceProvider::class);
    }

    protected function registerAdminGuard()
    {
        config()->set([
            'auth.guards.admin' => [
                'driver' => 'jwt',
                'provider' => 'admin_users'
            ],

            'auth.providers.admin_users' => [
                'driver' => 'eloquent',
                'model' => AdminUser::class,
            ],
        ]);
    }

    protected function registerAdminRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->controllerNamespace)
             ->group(function () {
                 Route::middleware('auth:admin')->group(function () {
                     Route::get('user', 'AdminUsersController@me');
                     Route::apiResource('users', 'AdminUsersController');
                     
                     Route::post('auth/logout', 'Auth\LoginController@logout');
                 });

                 Route::post('auth/login', 'Auth\LoginController@login')->middleware('guest:admin');
                 Route::post('auth/refresh', 'Auth\LoginController@refresh');
             });
    }
}
