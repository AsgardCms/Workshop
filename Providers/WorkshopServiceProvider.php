<?php namespace Modules\Workshop\Providers;

use Illuminate\Routing\Router;
use Illuminate\Support\ServiceProvider;
use Modules\Workshop\Scaffold\Generators\FilesGenerator;
use Modules\Workshop\Scaffold\ModuleScaffold;
use Modules\Workshop\Console\ScaffoldCommand;
use Modules\Workshop\Scaffold\Generators\EntityGenerator;
use Modules\Workshop\Scaffold\Generators\ValueObjectGenerator;

class WorkshopServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCommands();
    }

    /**
     * Register artisan commands
     */
    private function registerCommands()
    {
        $this->registerScaffoldCommand();

        $this->commands([
            'command.asgard.scaffold',
        ]);
    }

    /**
     * Register the scaffold command
     */
    private function registerScaffoldCommand()
    {
        $this->app->singleton('asgard.module.scaffold', function ($app) {
            return new ModuleScaffold(
                $app['files'],
                $app['config'],
                new EntityGenerator($app['files'], $app['config']),
                new ValueObjectGenerator($app['files'], $app['config']),
                new FilesGenerator($app['files'], $app['config'])
            );
        });

        $this->app->bindShared('command.asgard.scaffold', function ($app) {
            return new ScaffoldCommand($app['asgard.module.scaffold']);
        });
    }
}
