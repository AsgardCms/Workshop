<?php

use Illuminate\Routing\Router;
/** @var Router $router */

$router->post('/workshop/modules/{module}/publish', ['as' => 'api.workshop.module.publish', 'uses' => 'ModulesController@publishAssets']);
$router->post('/workshop/themes/{theme}/publish', ['as' => 'api.workshop.theme.publish', 'uses' => 'ThemeController@publishAssets']);
