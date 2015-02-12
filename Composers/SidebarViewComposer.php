<?php namespace Modules\Workshop\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->items->put('workbench', Collection::make([
            [
                'weight' => '1',
                'request' => Request::is("*/{$view->prefix}/workshop/modules*") or Request::is("*/{$view->prefix}/workshop/workbench*"),
                'route' => '#',
                'icon-class' => 'fa fa-cogs',
                'title' => 'Workshop',
                'permission' => $this->auth->hasAccess('workshop.modules.index') or $this->auth->hasAccess('workshop.workbench.index'),
            ],
            [
                'request' => "*/{$view->prefix}/workshop/modules*",
                'route' => 'admin.workshop.modules.index',
                'icon-class' => 'fa fa-cog',
                'title' => 'Modules',
                'permission' => $this->auth->hasAccess('workshop.modules.index'),
            ],
//            [
//                'request' => "*/{$view->prefix}/workshop/workbench*",
//                'route' => 'admin.workshop.workbench.index',
//                'icon-class' => 'fa fa-terminal',
//                'title' => 'Workbench',
//                'permission' => $this->auth->hasAccess('workshop.workbench.index')
//            ]
        ]));
    }
}
