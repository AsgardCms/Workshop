<?php namespace Modules\Workshop\Http\Controllers\Api;

use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Artisan;
use Pingpong\Modules\Module;

class ModulesController extends Controller
{
    public function publishAssets(Module $module)
    {
        Artisan::call('module:publish', ['module' => $module->getName()]);
    }
}
