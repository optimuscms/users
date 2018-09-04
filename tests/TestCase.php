<?php

namespace Optimus\Users\Tests;

use Orchestra\Testbench\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    public function setUp()
    {
        parent::setUp();
    }
    
    protected function getEnvironmentSetUp($app)
    {
        //
    }
}
