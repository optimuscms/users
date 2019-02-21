<?php

namespace Optimus\Users\Tests\Unit;

use Mockery;
use Illuminate\Http\Request;
use Optimus\Users\Tests\TestCase;
use Optimus\Users\Models\AdminUser;
use Optimus\Users\Http\Resources\AdminUserResource;

class AdminUserResourceTest extends TestCase
{
    /** @test */
    public function it_will_transform_user_data()
    {
        $user = new AdminUser();

        $user->id = 1;
        $user->name = 'Foo Bar';
        $user->email = 'foo@bar.com';
        $user->username = 'foobar';
        $user->password = 'fooBar123';
        $user->unknown_field = true;
        $user->created_at = now()->subDay();
        $user->updated_at = now();

        $resource = new AdminUserResource($user);

        $request = Mockery::mock(Request::class);

        $expected = [
            'id' => 1,
            'name' => $user->name,
            'email' => $user->email,
            'username' => $user->username,
            'created_at' => (string) $user->created_at,
            'updated_at' => (string) $user->updated_at
        ];

        $this->assertEquals(
            $expected, $resource->toArray($request)
        );
    }
}
