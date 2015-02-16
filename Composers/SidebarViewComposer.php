<?php namespace Modules\Workshop\Composers;

use Illuminate\Contracts\View\View;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;
use Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->sidebar->group(trans('workshop::workshop.title'), function (SidebarGroup $group) {
            $group->weight = 100;
            $group->authorize(
                $this->auth->hasAccess('workshop.modules.index') or $this->auth->hasAccess('workshop.workbench.index')
            );

            $group->addItem('Modules', function (SidebarItem $item) {
                $item->icon = 'fa fa-cogs';
                $item->route('admin.workshop.modules.index');
                $item->authorize(
                    $this->auth->hasAccess('workshop.modules.index')
                );
            });
        });
    }
}
