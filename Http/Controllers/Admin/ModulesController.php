<?php namespace Modules\Workshop\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\View;
use Laracasts\Flash\Flash;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Core\Services\Composer;
use Modules\Workshop\Http\Requests\ModulesRequest;
use Modules\Workshop\Manager\ModuleManager;
use Symfony\Component\Console\Output\BufferedOutput;

class ModulesController extends AdminBaseController
{
    /**
     * @var ModuleManager
     */
    private $moduleManager;
    /**
     * @var Composer
     */
    private $composer;

    public function __construct(ModuleManager $moduleManager, Composer $composer)
    {
        parent::__construct();

        $this->moduleManager = $moduleManager;
        $this->composer = $composer;
    }

    public function index()
    {
        $modules = $this->moduleManager->all();
        $coreModules = $this->moduleManager->getCoreModules();

        return View::make('workshop::admin.modules.index', compact('modules', 'coreModules'));
    }

    public function store(ModulesRequest $request)
    {
        $enabledModules = $this->moduleManager->getFlippedEnabledModules();

        $modules = $request->modules;
        foreach ($modules as $module => $value) {
            if (isset($enabledModules[$module])) {
                unset($enabledModules[$module]);
                unset($modules[$module]);
            }
        }

        $this->moduleManager->disableModules($enabledModules);
        $this->moduleManager->enableModules($modules);

        Flash::success('Modules configuration saved!');

        return Redirect::route('admin.workshop.modules.index');
    }

    /**
     * Update a given module
     * @param Request $request
     * @return Response json
     */
    public function update(Request $request)
    {
        $output = new BufferedOutput();
        Artisan::call('asgard:update', ['module' => $request->get('module')], $output);

        return Response::json(['updated' => true, 'message' => $output->fetch()]);
    }
}
