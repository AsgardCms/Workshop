<?php namespace Modules\Workshop\Composers;

use Illuminate\Contracts\View\View;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Sidebar\SidebarGroup;
use Maatwebsite\Sidebar\SidebarItem;
use Modules\Core\Composers\BaseSidebarViewComposer;

class SidebarViewComposer extends BaseSidebarViewComposer
{
    public function compose(View $view)
    {
        $view->sidebar->group('Workshop', function (SidebarGroup $group) {
            $group->weight = 6;
            $group->authorize(
                $this->auth->hasAccess('workshop.modules.index') or $this->auth->hasAccess('workshop.workbench.index')
            );

            $group->addItem('Modules', function (SidebarItem $item) {
                $item->route('admin.workshop.modules.index');
                $item->icon = 'fa fa-cogs';
                $item->name = 'Modules';
                $item->authorize(
                    $this->auth->hasAccess('workshop.modules.index')
                );
            });
        });

    }
}
