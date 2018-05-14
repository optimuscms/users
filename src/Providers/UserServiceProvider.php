<?php

namespace Optimus\Users\Providers;

use Optimus\Users\AdminUser;
use Illuminate\Support\ServiceProvider;
use Tymon\JWTAuth\Providers\LaravelServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    protected $controllerNamespace = 'Optimus\Users\Http\Controllers';

    public function boot()
    {
        $this->registerAdminGuard();

        $this->registerAdminRoutes();
    }

    public function register()
    {
        $this->app->register(LaravelServiceProvider::class);
    }

    protected function registerAdminGuard()
    {
        // Todo: Passwords

        config()->set([
            'auth.guards.admin' => [
                'driver' => 'jwt',
                'provider' => 'admins'
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
                 Route::middlware('auth:admin')->group(function () {
                     Route::get('user', 'AdminUsersController@show');
                     Route::apiResource('users', 'AdminUsersController');

                     Route::post('auth/logout', 'LoginController@logout');
                 });

                 Route::post('auth/login', 'LoginController@login')->middleware('guest:admin');
             });
    }
}
