<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->bind('module', function ($module) {
    return app(\Pingpong\Modules\Repository::class)->find($module);
});
$router->bind('theme', function ($theme) {
    return app(\Modules\Workshop\Manager\ThemeManager::class)->find($theme);
});

$router->group(['prefix' => '/workshop'],
    function (Router $router) {
        $router->get('modules', ['as' => 'admin.workshop.modules.index', 'uses' => 'ModulesController@index']);
        $router->get('modules/{module}', ['as' => 'admin.workshop.modules.show', 'uses' => 'ModulesController@show']);
        $router->post('modules', ['as' => 'admin.workshop.modules.store', 'uses' => 'ModulesController@store']);
        $router->post('modules/update', ['as' => 'admin.workshop.modules.update', 'uses' => 'ModulesController@update']);
        $router->post('modules/disable/{module}', ['as' => 'admin.workshop.modules.disable', 'uses' => 'ModulesController@disable']);
        $router->post('modules/enable/{module}', ['as' => 'admin.workshop.modules.enable', 'uses' => 'ModulesController@enable']);

        $router->get('themes', ['as' => 'admin.workshop.themes.index', 'uses' => 'ThemesController@index']);
        $router->get('themes/{theme}', ['as' => 'admin.workshop.themes.show', 'uses' => 'ThemesController@show']);

        # Workbench
        $router->get('workbench', ['as' => 'admin.workshop.workbench.index', 'uses' => 'WorkbenchController@index']);
        $router->post('generate', ['as' => 'admin.workshop.workbench.generate.index', 'uses' => 'WorkbenchController@generate']);
        $router->post('migrate', ['as' => 'admin.workshop.workbench.migrate.index', 'uses' => 'WorkbenchController@migrate']);
        $router->post('install', ['as' => 'admin.workshop.workbench.install.index', 'uses' => 'WorkbenchController@install']);
        $router->post('seed', ['as' => 'admin.workshop.workbench.seed.index', 'uses' => 'WorkbenchController@seed']);
    }
);
