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
        $this->loadMigrationsFrom(__DIR__ . '/../../database/migrations');

        $this->registerAdminGuard();

        $this->mapAdminRoutes();
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

    protected function mapAdminRoutes()
    {
        Route::prefix('api')
             ->middleware('api')
             ->namespace($this->controllerNamespace)
             ->group(function () {
                 Route::middlware('auth:admin')->group(function () {
                     Route::get('user', 'AdminUsersController@me');
                     Route::apiResource('users', 'AdminUsersController');

                     // Todo: Refresh
                     Route::post('auth/logout', 'LoginController@logout');
                 });

                 Route::post('auth/login', 'LoginController@login')->middleware('guest:admin');
             });
    }
}
