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
            'Pingpong\Modules\ModulesServiceProvider',
        ];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['path.base'] = realpath(__DIR__ . '/../Modules');
        $app['config']->set('modules.namespace', 'Modules');
        $app['config']->set('app.locale', 'en');
        $app['config']->set('modules.paths.generator', [
            'assets' => 'Assets',
            'config' => 'Config',
            'command' => 'Console',
            'migration' => 'Database/Migrations',
            'model' => 'Entities',
            'repository' => 'Repositories',
            'seeder' => 'Database/Seeders',
            'controller' => 'Http/Controllers',
            'filter' => 'Http/Middleware',
            'request' => 'Http/Requests',
            'provider' => 'Providers',
            'lang' => 'Resources/lang',
            'views' => 'Resources/views',
            'test' => 'Tests',
        ]);
        $app['config']->set('laravellocalization.supportedLocales', [
            'en' => ['name' => 'English', 'script' => 'Latn', 'native' => 'English'],
            'fr' => ['name' => 'French', 'script' => 'Latn', 'native' => 'français'],
        ]);
        $app['config']->set('modules.paths.modules', realpath(__DIR__ . '/../Modules'));
    }
}
