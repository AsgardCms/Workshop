<?php namespace Modules\Workshop\Http\Controllers\Admin;

use FloatingPoint\Stylist\Theme\Theme;
use Illuminate\Filesystem\Filesystem;
use Modules\Core\Http\Controllers\Admin\AdminBaseController;
use Modules\Workshop\Manager\ThemeManager;

class ThemesController extends AdminBaseController
{
    /**
     * @var ThemeManager
     */
    private $themeManager;

    public function __construct(ThemeManager $themeManager)
    {
        parent::__construct();

        $this->themeManager = $themeManager;
    }

    public function show(Theme $theme)
    {
        return view('workshop::admin.themes.show', compact('theme'));
    }
}
