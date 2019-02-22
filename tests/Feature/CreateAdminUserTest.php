<?php

namespace Optimus\Users\Tests\Feature;

use Optimus\Users\Tests\TestCase;
use Optimus\Users\Models\AdminUser;
use Illuminate\Support\Facades\Hash;

class CreateAdminUserTest extends TestCase
{
    /** @test */
    public function it_can_create_an_admin_user()
    {
        $this->signIn();

        $response = $this->postJson(route('admin.users.store'), $data = [
            'name' => 'Jack Robertson',
            'email' => 'jack@optixsolutions.co.uk',
            'username' => 'jack',
            'password' => 'password'
        ]);

        $response
            ->assertStatus(201)
            ->assertJsonStructure([
                'data' => $this->expectedJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'name' => $data['name'],
                    'email' => $data['email'],
                    'username' => $data['username']
                ]
            ]);

        $this->assertNotNull($user = AdminUser::find(
            $response->decodeResponseJson('data.id')
        ));

        $this->assertTrue(Hash::check(
            $data['password'],
            $user->password
        ));
    }
}
