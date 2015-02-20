<?php namespace Modules\Workshop\Tests;

use Orchestra\Testbench\TestCase;

abstract class BaseTestCase extends TestCase
{
    public function setUp()
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [
            'Modules\Workshop\Providers\WorkshopServiceProvider',
            'Pingpong\Modules\ModulesServiceProvider'
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app->setBasePath(__DIR__ . '/..');
    }
}
