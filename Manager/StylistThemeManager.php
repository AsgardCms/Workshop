<?php namespace Modules\Workshop\Manager;

use FloatingPoint\Stylist\Theme\Json;
use FloatingPoint\Stylist\Theme\Loader;
use FloatingPoint\Stylist\Theme\Theme;
use Illuminate\Filesystem\Filesystem;

class StylistThemeManager implements ThemeManager
{
    /**
     * @var Filesystem
     */
    private $finder;

    public function __construct(Filesystem $finder)
    {
        $this->finder = $finder;
    }

    /**
     * @return array
     */
    public function all()
    {
        $themePath = config('stylist.themes.paths', [base_path('/Themes')]);
        $directories = $this->finder->directories($themePath[0]);

        $themes = [];
        foreach ($directories as $directory) {
            $themes[] = $this->getThemeInfoForPath($directory);
        }

        return $themes;
    }

    /**
     * @param string $directory
     * @return Theme
     */
    private function getThemeInfoForPath($directory)
    {
        $themeJson = new Json($directory);

        $theme = new Theme(
            $themeJson->getJsonAttribute('name'),
            $themeJson->getJsonAttribute('description'),
            $directory,
            $themeJson->getJsonAttribute('parent')
        );
        $theme->version = $themeJson->getJsonAttribute('version');
        $theme->type = $themeJson->getJsonAttribute('type');

        return $theme;
    }
}
