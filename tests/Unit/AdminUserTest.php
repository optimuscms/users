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

        $user->fill([
            'name' => 'Foo Bar',
            'email' => 'foo@bar.com',
            'username' => 'foobar',
            'password' => 'fooBar123',
            'unknown_field' => true
        ]);

        $attributes = $user->getAttributes();

        $this->assertNotContains(
            'unknown_field', $user->getAttributes()
        );

        $this->assertEmpty(array_diff(
            ['name', 'email', 'username', 'password'],
            $attributes
        ));
    }

    /** @test */
    public function it_hides_the_password_field_when_cast_to_an_array()
    {
        $user = new AdminUser();

        $user->password = 'fooBar123';

        $this->assertNotContains(
            'password', $user->toArray()
        );
    }
}
