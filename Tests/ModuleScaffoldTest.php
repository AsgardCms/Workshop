<?php namespace Modules\Workshop\Tests;

use Modules\Core\Tests\BaseTestCase;
use Modules\Workshop\Scaffold\ModuleScaffold;
use Modules\Workshop\Scaffold\Generators\FilesGenerator;
use Modules\Workshop\Scaffold\Generators\EntityGenerator;
use Modules\Workshop\Scaffold\Generators\ValueObjectGenerator;

class ModuleScaffoldTest extends BaseTestCase
{
    /**
     * @var ModuleScaffold
     */
    protected $scaffold;

    public function setUp()
    {
        parent::setUp();

        $this->scaffold = new ModuleScaffold(
            $this->app['artisan'],
            $this->app['files'],
            $this->app['config'],
            new EntityGenerator($this->app['files'], $this->app['config']),
            new ValueObjectGenerator($this->app['files'], $this->app['config']),
            new FilesGenerator($this->app['files'], $this->app['config'])
        );
    }


}
