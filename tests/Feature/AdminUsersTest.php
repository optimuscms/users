<?php

namespace Optimus\Users\Tests\Feature;

use Optimus\Users\Tests\TestCase;
use Optimus\Users\Models\AdminUser;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AdminUsersTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_display_all_admin_users()
    {
        $users = factory(AdminUser::class, 3)->create();

        $this->signIn($users->first());

        $response = $this->getJson(route('admin.users.index'));

        $response
            ->assertOk()
            ->assertJsonCount(3, 'data')
            ->assertJsonStructure([
                'data' => [
                    '*' => $this->expectedJsonStructure()
                ]
            ]);
    }

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
            $data['password'], $user->password
        ));
    }

    /** @test */
    public function it_can_display_a_specific_admin_user()
    {
        $user = factory(AdminUser::class)->create();

        $this->signIn($user);

        $response = $this->getJson(route('admin.users.show', [
            'id' => $user->id
        ]));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'created_at' => (string) $user->created_at,
                    'updated_at' => (string) $user->updated_at
                ]
            ]);
    }

    /** @test */
    public function it_can_display_the_currently_authenticated_admin_user()
    {
        $user = $this->signIn();

        $response = $this->getJson(route('admin.users.authenticated'));

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'username' => $user->username,
                    'created_at' => (string) $user->created_at,
                    'updated_at' => (string) $user->updated_at
                ]
            ]);
    }

    /** @test */
    public function it_can_update_an_admin_user()
    {
        $user = factory(AdminUser::class)->create([
            'name' => 'Old name',
            'email' => 'old@email.com',
            'username' => 'old_username',
            'password' => bcrypt('old_password')
        ]);

        $this->signIn($user);

        $response = $this->patchJson(route('admin.users.update', [
            'id' => $user->id
        ]), $newData = [
            'name' => 'New name',
            'email' => 'new@email.com',
            'username' => 'new_username',
            'password' => 'new_password'
        ]);

        $response
            ->assertOk()
            ->assertJsonStructure([
                'data' => $this->expectedJsonStructure()
            ])
            ->assertJson([
                'data' => [
                    'name' => $newData['name'],
                    'email' => $newData['email'],
                    'username' => $newData['username']
                ]
            ]);

        $this->assertTrue(Hash::check(
            $newData['password'], $user->fresh()->password
        ));
    }

    /** @test */
    public function it_can_delete_an_admin_user()
    {
        $this->signIn();

        $user = factory(AdminUser::class)->create();

        $response = $this->deleteJson(route('admin.users.destroy', [
            'id' => $user->id
        ]));

        $response->assertStatus(204);

        $this->assertDatabaseMissing($user->getTable(), [
            'id' => $user->id
        ]);
    }

    protected function expectedJsonStructure()
    {
        return [
            'id',
            'name',
            'email',
            'username',
            'created_at',
            'updated_at'
        ];
    }
}
