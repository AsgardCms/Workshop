<?php namespace Modules\Workshop\Tests;

use Modules\Core\Tests\BaseTestCase;
use Modules\Workshop\Scaffold\ModuleScaffold;
use Modules\Workshop\Scaffold\Generators\FilesGenerator;
use Modules\Workshop\Scaffold\Generators\EntityGenerator;
use Modules\Workshop\Scaffold\Generators\ValueObjectGenerator;

class ModuleScaffoldTest extends BaseTestCase
{
    /**
     * @var ModuleScaffold
     */
    protected $scaffold;
    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $finder;
    /**
     * @var string Path to the module under test
     */
    protected $testModulePath;
    /**
     * @var string The name of the module under test
     */
    protected $testModuleName;

    public function setUp()
    {
        parent::setUp();

        $this->finder = $this->app['files'];
        $this->scaffold = $this->app['asgard.module.scaffold'];
        $this->testModuleName = 'TestingTestModule';
        $this->testModulePath = base_path("Modules/{$this->testModuleName}");
    }

    /**
     *
     */
    private function cleanUp()
    {
        $this->finder->deleteDirectory($this->testModulePath);
    }

    /**
     * Scaffold a test module using eloquent
     * @param array $entities
     * @param array $valueObjects
     */
    private function scaffoldModuleWithEloquent(array $entities = ['Post'], array $valueObjects = [])
    {
        $this->scaffold
            ->vendor('asgardcms')
            ->name($this->testModuleName)
            ->setEntityType('Eloquent')
            ->withEntities($entities)
            ->withValueObjects($valueObjects)
            ->scaffold();
    }

    /**
     * Scaffold a test module using doctrine
     * @param array $entities
     * @param array $valueObjects
     */
    private function scaffoldModuleWithDoctrine(array $entities = ['Post'], array $valueObjects = [])
    {
        $this->scaffold
            ->vendor('asgardcms')
            ->name($this->testModuleName)
            ->setEntityType('Doctrine')
            ->withEntities($entities)
            ->withValueObjects($valueObjects)
            ->scaffold();
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory($this->testModulePath);
    }

    /** @test */
    public function it_should_generate_module_folder()
    {
        // Run
        $this->scaffoldModuleWithEloquent();

        // Assert
        $this->assertTrue($this->finder->isDirectory($this->testModulePath));

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_eloquent_entities_with_translations()
    {
        // Run
        $this->scaffoldModuleWithEloquent(['Category', 'Post']);

        // Assert
        $entities = $this->finder->allFiles($this->testModulePath . '/Entities');
        $this->assertCount(4, $entities);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_doctrine_entities_with_translations()
    {
        // Run
        $this->scaffoldModuleWithDoctrine(['Category', 'Post']);

        // Assert
        $entities = $this->finder->allFiles($this->testModulePath . '/Entities');
        $this->assertCount(4, $entities);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_translation_entities()
    {
        // Run
        $this->scaffoldModuleWithEloquent();

        // Assert
        $entity = $this->finder->isFile($this->testModulePath . '/Entities/Post.php');
        $translationEntity = $this->finder->isFile($this->testModulePath . '/Entities/PostTranslation.php');
        $this->assertTrue($entity);
        $this->assertTrue($translationEntity);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_cache_decorators()
    {
        // Run
        $this->scaffoldModuleWithEloquent(['Category', 'Post']);

        // Assert
        $categoryDecorator = $this->finder->isFile($this->testModulePath . '/Repositories/Cache/CacheCategoryDecorator.php');
        $postDecorator = $this->finder->isFile($this->testModulePath . '/Repositories/Cache/CachePostDecorator.php');
        $this->assertTrue($categoryDecorator);
        $this->assertTrue($postDecorator);

        $this->cleanUp();
    }
}
