<?php namespace Modules\Workshop\Scaffold\Theme;

use Illuminate\Filesystem\Filesystem;
use Modules\Workshop\Scaffold\Theme\Exceptions\ThemeExistsException;

class ThemeScaffold
{
    /**
     * @var array
     */
    protected $files = [
        'themeJson',
        'gulpfileJs',
        'packageJson',
        'baseLayout',
    ];

    /**
     * @var ThemeGeneratorFactory
     */
    private $themeGeneratorFactory;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    private $finder;

    public function __construct(ThemeGeneratorFactory $themeGeneratorFactory, Filesystem $finder)
    {
        $this->themeGeneratorFactory = $themeGeneratorFactory;
        $this->finder = $finder;
    }

    public function generate($name)
    {
        if ($this->finder->isDirectory($this->themePath($name))) {
            throw new ThemeExistsException();
        }

        $this->finder->makeDirectory($this->themePath($name));

        foreach ($this->files as $file) {
            $this->themeGeneratorFactory->make($file)->generate();
        }
    }

    /**
     * Set the files array on the class
     * @param array $files
     */
    public function setFiles(array $files)
    {
        $this->files = $files;
    }

    /**
     * @param string $name
     * @return string
     */
    private function themePath($name = '')
    {
        return config('stylist.themes.paths')[0] . "/$name";
    }
}
