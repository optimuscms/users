<?php

namespace Optimus\Users\Tests\Feature;

use Optimus\Users\Tests\TestCase;
use Optimus\Users\Models\AdminUser;

class AuthenticationTest extends TestCase
{
    protected $user;

    public function setUp()
    {
        parent::setUp();

        $this->user = AdminUser::create([
            'name' => 'Optix',
            'email' => 'dev@optixsolutions.co.uk',
            'username' => 'admin',
            'password' => bcrypt('password')
        ]);
    }

    /** @test */
    public function a_user_can_make_authenticated_requests_to_the_api()
    {
        $response = $this->postJson('api/auth/login', [
            'username' => $this->user->username,
            'password' => 'password'
        ]);

        $response->assertOk()
                 ->assertJsonStructure([
                     'access_token'
                 ]);

        $token = $response->decodeResponseJson('access_token');

        $this->refreshApplication();
        $this->setUp();

        $response = $this->getJson('api/admin-user', [
            'Authorization' => "Bearer {$token}"
        ]);

        $response->assertOk();
    }

    public function an_authenticated_user_can_refresh_their_token()
    {
        $this->assertTrue(true);
    }
}
