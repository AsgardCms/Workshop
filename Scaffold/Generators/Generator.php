<?php namespace Modules\Workshop\Scaffold\Generators;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Filesystem\Filesystem;

abstract class Generator
{
    /**
     * @var Filesystem
     */
    protected $finder;
    /**
     * @var string Module Name
     */
    protected $name;
    /**
     * @var Repository
     */
    private $config;

    public function __construct(Filesystem $finder, Repository $config)
    {
        $this->finder = $finder;
        $this->config = $config;
    }

    /**
     * Generate the given files
     * @param  array $files
     * @return void
     */
    abstract public function generate(array $files);

    /**
     * Set the module name
     * @param  string $moduleName
     * @return $this
     */
    public function forModule($moduleName)
    {
        $this->name = $moduleName;

        return $this;
    }

    /**
     * Return the current module path
     * @param  string $path
     * @return string
     */
    protected function getModulesPath($path = '')
    {
        return $this->config->get('modules::paths.modules')."/{$this->name}/$path";
    }

    /**
     * Get the path the stubs for the given filename
     *
     * @param $filename
     * @return string
     */
    protected function getStubPath($filename)
    {
        return __DIR__."/../stubs/$filename";
    }

    /**
     * Write the given content to the given file
     * @param string $path
     * @param string $content
     */
    protected function writeFile($path, $content)
    {
        $this->finder->put("$path.php", $content);
    }
}
