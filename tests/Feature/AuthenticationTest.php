<?php

namespace Optimus\Users\Tests\Feature;

use Optimus\Users\Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;
use Optimus\Users\Models\AdminUser;
use Tymon\JWTAuth\Contracts\JWTSubject;

class AuthenticationTest extends TestCase
{
    /** @test */
    public function a_user_can_request_an_access_token()
    {
        $user = AdminUser::create([
            'name' => 'Optix',
            'email' => 'dev@optixsolutions.co.uk',
            'username' => 'admin',
            'password' => bcrypt('password')
        ]);

        $response = $this->postJson('api/auth/login', [
            'username' => $user->username,
            'password' => 'password'
        ]);

        $response->assertOk()
                 ->assertJsonStructure([
                     'access_token'
                 ]);

//        $token = $response->decodeResponseJson('access_token');
//
//        // Todo: Swap out in-memory database.
//        $this->refreshApplication();
//
//        $response = $this->getJson('api/admin-user', [
//            'Authorization' => "Bearer {$token}"
//        ]);
//
//        $response->assertOk();
    }

    /** @test */
    public function a_user_can_refresh_their_access_token()
    {
        $user = AdminUser::create([
            'name' => 'Optix',
            'email' => 'dev@optixsolutions.co.uk',
            'username' => 'admin',
            'password' => bcrypt('password')
        ]);

        $response = $this->authenticate($user)->postJson('api/auth/refresh');

        $response->assertOk()
                 ->assertJsonStructure([
                     'access_token'
                 ]);
    }

    // access tokens are invalidated when refreshed

    // a user can refresh an expired token issued within the refresh ttl

    // an exception is thrown when a user tries to refresh a token outside of the refresh ttl

    // a user can invalidate their access token

    public function authenticate(JWTSubject $user)
    {
        $token = JWTAuth::fromUser($user);

        return $this->withHeader('Authorization', "Bearer {$token}");
    }
}
