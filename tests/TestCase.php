<?php

namespace Optimus\Users\Tests;

use Optimus\Users\Models\AdminUser;
use Optimus\Users\UserServiceProvider;
use Illuminate\Contracts\Auth\Authenticatable;
use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    protected function setUp()
    {
        parent::setUp();

        $this->withFactories(
            __DIR__ . '/../database/factories'
        );
    }

    protected function getPackageProviders($app)
    {
        return [
            UserServiceProvider::class,
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        // Auth
        $app['config']->set('auth.guards.admin', [
            'driver' => 'session',
            'provider' => 'admins'
        ]);

        $app['config']->set('auth.providers.admins', [
            'driver' => 'eloquent',
            'model' => AdminUser::class
        ]);

        // Database
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]);

        $app['config']->set('database.default', 'sqlite');
        $app['config']->set('database.connections.sqlite', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => ''
        ]);
    }

    protected function signIn(Authenticatable $user = null)
    {
        $user = $user ?: factory(AdminUser::class)->create();

        $this->actingAs($user, 'admin');

        return $user;
    }
}
