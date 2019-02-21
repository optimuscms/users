<?php

namespace Optimus\Users\Tests\Unit;

use Optimus\Users\Tests\TestCase;
use Optimus\Users\Models\AdminUser;

class AdminUserTest extends TestCase
{
    /** @test */
    public function it_has_fillable_fields()
    {
        $user = new AdminUser();

        $user->fill($attributes = [
            'name' => 'Foo Bar',
            'email' => 'foo@bar.com',
            'username' => 'foobar',
            'password' => 'fooBar123',
            'unknown_field' => true
        ]);

        $this->assertEquals($attributes['name'], $user->getAttribute('name'));
        $this->assertEquals($attributes['email'], $user->getAttribute('email'));
        $this->assertEquals($attributes['username'], $user->getAttribute('username'));
        $this->assertEquals($attributes['password'], $user->getAttribute('password'));
        $this->assertNotContains('unknown_field', $user->getAttributes());
    }

    /** @test */
    public function it_hides_the_password_field_when_serialised()
    {
        $user = new AdminUser();

        $user->password = 'fooBar123';

        $this->assertNotContains('password', $user->toArray());
    }
}
