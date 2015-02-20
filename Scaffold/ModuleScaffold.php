<?php namespace Modules\Workshop\Scaffold;

use Illuminate\Contracts\Config\Repository;
use Illuminate\Contracts\Console\Kernel;
use Illuminate\Filesystem\Filesystem;
use Modules\Workshop\Scaffold\Exception\ModuleExistsException;
use Modules\Workshop\Scaffold\Generators\EntityGenerator;
use Modules\Workshop\Scaffold\Generators\FilesGenerator;
use Modules\Workshop\Scaffold\Generators\ValueObjectGenerator;

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
     * @var array of files to generate
     */
    protected $files = [
        'sidebar-view-composer.stub' => 'Composers/SidebarViewComposer',
        'permissions.stub' => 'Config/permissions',
        'routes.stub' => 'Http/backendRoutes',
        'route-provider.stub' => 'Providers/RouteServiceProvider',
        'composers.stub' => 'composers',
    ];
    /**
     * @var string The type of entities to generate [Eloquent or Doctrine]
     */
    protected $entityType;
    /**
     * @var Kernel
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
    /**
     * @var ValueObjectGenerator
     */
    private $valueObjectGenerator;
    /**
     * @var FilesGenerator
     */
    private $filesGenerator;

    public function __construct(
        Filesystem $finder,
        Repository $config,
        EntityGenerator $entityGenerator,
        ValueObjectGenerator $valueObjectGenerator,
        FilesGenerator $filesGenerator
    ) {
        $this->artisan = app('Illuminate\Contracts\Console\Kernel');
        $this->finder = $finder;
        $this->config = $config;
        $this->entityGenerator = $entityGenerator;
        $this->valueObjectGenerator = $valueObjectGenerator;
        $this->filesGenerator = $filesGenerator;
    }

    /**
     *
     */
    public function scaffold()
    {
        if ($this->finder->isDirectory($this->getModulesPath())) {
            throw new ModuleExistsException();
        }

        $this->artisan->call("module:make", ['name' => [$this->name]]);

        $this->removeUnneededFiles();
        $this->addFolders();

        $this->filesGenerator->forModule($this->name)
            ->generateModuleProvider()
            ->generate($this->files);

        $this->loadProviders();

        $this->entityGenerator->forModule($this->name)->type($this->entityType)->generate($this->entities);
        $this->valueObjectGenerator->forModule($this->name)->type($this->entityType)->generate($this->valueObjects);
    }

    /**
     * @param  string $vendor
     * @return $this
     */
    public function vendor($vendor)
    {
        $this->vendor = $vendor;

        return $this;
    }

    /**
     * @param  string $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Set the entity type [Eloquent, Doctrine]
     * @param  string $entityType
     * @return $this
     */
    public function setEntityType($entityType)
    {
        $this->entityType = $entityType;

        return $this;
    }

    /**
     * @param  array $entities
     * @return $this
     */
    public function withEntities(array $entities)
    {
        $this->entities = $entities;

        return $this;
    }

    /**
     * @param  array $valueObjects
     * @return $this
     */
    public function withValueObjects(array $valueObjects)
    {
        $this->valueObjects = $valueObjects;

        return $this;
    }

    /**
     * Return the current module path
     * @param  string $path
     * @return string
     */
    private function getModulesPath($path = '')
    {
        return $this->config->get('modules.paths.modules') . "/{$this->name}/$path";
    }

    /**
     * Remove the start.php start file
     * Also removes the auto loading of that file
     */
    private function renameStartFileToComposersFile()
    {
        $this->finder->delete($this->getModulesPath('start.php'));
        $moduleJsonContent = $this->finder->get($this->getModulesPath('module.json'));
        $moduleJsonContent = str_replace('"start.php"', '"composers.php"', $moduleJsonContent);
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

    /**
     * Remove all unneeded files
     */
    private function removeUnneededFiles()
    {
        $this->renameStartFileToComposersFile();
        $this->renameVendorName();
        $this->removeViewResources();

        $this->finder->delete($this->getModulesPath('Http/routes.php'));
        $this->finder->delete($this->getModulesPath("Http/Controllers/{$this->name}Controller.php"));
    }

    private function loadProviders()
    {
        $providerContent = $this->finder->get($this->getModulesPath('module.json'));
        $newProviders = <<<JSON
"Modules\\\\{$this->name}\\\Providers\\\\{$this->name}ServiceProvider",
    "Modules\\\\{$this->name}\\\Providers\\\RouteServiceProvider"
JSON;

        $oldProvider = '"Modules\\\\'.$this->name.'\\\\Providers\\\\'.$this->name.'ServiceProvider"';
        $providerContent = str_replace($oldProvider, $newProviders, $providerContent);
        $this->finder->put($this->getModulesPath('module.json'), $providerContent);
    }

    private function addFolders()
    {
        $this->finder->makeDirectory($this->getModulesPath('Composers'));
        $this->finder->makeDirectory($this->getModulesPath('Repositories/Cache'));
    }
}
