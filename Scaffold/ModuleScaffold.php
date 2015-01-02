<?php namespace Modules\Workshop\Scaffold;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Console\Application;
use Illuminate\Filesystem\Filesystem;

class ModuleScaffold
{
    /**
     * Contains the vendor name
     * @var string
     */
    protected $vendor;
    /**
     * Contains the Module name
     * @var string
     */
    protected $name;
    /**
     * Contains an array of entities to generate
     * @var array
     */
    protected $entities;
    /**
     * Contains an array of value objects to generate
     * @var array
     */
    protected $valueObjects;
    /**
     * @var Application
     */
    private $artisan;
    /**
     * @var Filesystem
     */
    private $finder;
    /**
     * @var Repository
     */
    private $config;
    /**
     * @var EntityGenerator
     */
    private $entityGenerator;

    public function __construct(
        Application $artisan,
        Filesystem $finder,
        Repository $config,
        EntityGenerator $entityGenerator
    )
    {
        $this->artisan = $artisan;
        $this->finder = $finder;
        $this->config = $config;
        $this->entityGenerator = $entityGenerator;
    }

    /**
     *
     */
    public function scaffold()
    {
        $this->artisan->call("module:make", ['name' => [$this->name]]);

        $this->removeStartFile();
        $this->renameVendorName();
        $this->removeViewResources();

        $this->entityGenerator->forModule($this->name)->generate($this->entities);
        // generate value objects
        // generate files (repositories, SidebarViewComposer, config/permissions)
    }

    /**
     * @param string $vendor
     * @return $this
     */
    public function vendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @param string $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @param array $entities
     * @return $this
     */
    public function withEntities(array $entities)
    {
        $this->entities = $entities;

        return $this;
    }

    /**
     * @param array $valueObjects
     * @return $this
     */
    public function withValueObjects(array $valueObjects)
    {
        $this->valueObjects = $valueObjects;

        return $this;
    }

    /**
     * Return the current module path
     * @param string $path
     * @return string
     */
    private function getModulesPath($path = '')
    {
        return $this->config->get('modules::paths.modules') . "/{$this->name}/$path";
    }

    /**
     * Remove the start.php start file
     * Also removes the auto loading of that file
     */
    private function removeStartFile()
    {
        $this->finder->delete($this->getModulesPath('start.php'));
        $moduleJsonContent = $this->finder->get($this->getModulesPath('module.json'));
        $moduleJsonContent = str_replace('"start.php"', '', $moduleJsonContent);
        $this->finder->put($this->getModulesPath('module.json'), $moduleJsonContent);
    }

    /**
     * Rename the default vendor name 'pingpong-modules'
     * by the input vendor name
     */
    private function renameVendorName()
    {
        $composerJsonContent = $this->finder->get($this->getModulesPath('composer.json'));
        $composerJsonContent = str_replace('pingpong-modules', $this->vendor, $composerJsonContent);
        $this->finder->put($this->getModulesPath('composer.json'), $composerJsonContent);
    }

    /**
     * Remove the default generated view resources
     */
    private function removeViewResources()
    {
        $this->finder->delete($this->getModulesPath('Resources/views/index.blade.php'));
        $this->finder->delete($this->getModulesPath('Resources/views/layouts/master.blade.php'));
        $this->finder->deleteDirectory($this->getModulesPath('Resources/views/layouts'));
    }
}
