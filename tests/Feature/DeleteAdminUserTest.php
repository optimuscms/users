<?php

namespace Optimus\Users\Tests\Feature;

use Optimus\Users\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class DeleteAdminUserTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_delete_an_admin_user()
    {
        $user = $this->signIn();

        $response = $this->deleteJson(route('admin.users.destroy', [
            'id' => $user->id
        ]));

        $response->assertStatus(204);

        $this->assertDatabaseMissing($user->getTable(), [
            'id' => $user->id
        ]);
    }
}
