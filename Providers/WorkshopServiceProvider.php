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
     * The filters base class name.
     *
     * @var array
     */
    protected $filters = [
        'Core' => [
            'permissions' => 'PermissionFilter',
        ],
    ];

    /**
     * Register the filters.
     *
     * @param  Router $router
     * @return void
     */
    public function registerFilters(Router $router)
    {
        foreach ($this->filters as $module => $filters) {
            foreach ($filters as $name => $filter) {
                $class = "Modules\\{$module}\\Http\\Filters\\{$filter}";

                $router->filter($name, $class);
            }
        }
    }

    /**
     * Register the service provider.
     *
     * @return void
     */
    public function register()
    {
        $this->app->booted(function ($app) {
            $this->registerFilters($app['router']);
        });
        $this->registerCommands();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return array();
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
        $this->app->bindShared('command.asgard.scaffold', function ($app) {
            $moduleScaffold = new ModuleScaffold(
                $app['artisan'],
                $app['files'],
                $app['config'],
                new EntityGenerator($app['files'], $app['config']),
                new ValueObjectGenerator($app['files'], $app['config']),
                new FilesGenerator($app['files'], $app['config'])
            );

            return new ScaffoldCommand($moduleScaffold);
        });
    }
}
