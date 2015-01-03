<?php

use Illuminate\Routing\Router;

$router->group(['prefix' => LaravelLocalization::setLocale(), 'before' => 'LaravelLocalizationRedirectFilter|auth.admin|permissions'], function (Router $router) {
    $router->group(['prefix' => Config::get('core::core.admin-prefix') . '/workshop', 'namespace' => 'Modules\Workshop\Http\Controllers'],
        function (Router $router) {
            $router->get('modules', ['as' => 'admin.workshop.modules.index', 'uses' => 'ModulesController@index']);
            $router->post('modules', ['as' => 'admin.workshop.modules.store', 'uses' => 'ModulesController@store']);
            # Workbench
            $router->get('workbench', ['as' => 'admin.workshop.workbench.index', 'uses' => 'WorkbenchController@index']);
            $router->post('generate', ['as' => 'admin.workshop.workbench.generate.index', 'uses' => 'WorkbenchController@generate']);
            $router->post('migrate', ['as' => 'admin.workshop.workbench.migrate.index', 'uses' => 'WorkbenchController@migrate']);
            $router->post('install', ['as' => 'admin.workshop.workbench.install.index', 'uses' => 'WorkbenchController@install']);
            $router->post('seed', ['as' => 'admin.workshop.workbench.seed.index', 'uses' => 'WorkbenchController@seed']);
        }
    );
});
