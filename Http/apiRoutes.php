<?php


post('/workshop/modules/{module}/publish', ['as' => 'api.workshop.module.publish', 'uses' => 'ModulesController@publishAssets']);
post('/workshop/themes/{theme}/publish', ['as' => 'api.workshop.theme.publish', 'uses' => 'ThemeController@publishAssets']);
