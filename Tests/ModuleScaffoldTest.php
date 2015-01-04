<?php namespace Modules\Workshop\Tests;

use Modules\Core\Tests\BaseTestCase;
use Modules\Workshop\Scaffold\ModuleScaffold;

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

    /** @test */
    public function it_should_generate_repository_interfaces()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $interface = $this->finder->isFile($this->testModulePath . '/Repositories/PostRepository.php');
        $interface2 = $this->finder->isFile($this->testModulePath . '/Repositories/CategoryRepository.php');

        $this->assertTrue($interface);
        $this->assertTrue($interface2);
    }

    /** @test */
    public function it_should_generate_eloquent_repositories()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $repository = $this->finder->isFile($this->testModulePath . '/Repositories/Eloquent/EloquentPostRepository.php');
        $repository2 = $this->finder->isFile($this->testModulePath . '/Repositories/Eloquent/EloquentCategoryRepository.php');

        $this->assertTrue($repository);
        $this->assertTrue($repository2);
    }

    /** @test */
    public function it_should_generate_doctrine_repositories()
    {
        $this->scaffoldModuleWithDoctrine(['Post', 'Category']);

        $repository = $this->finder->isFile($this->testModulePath . '/Repositories/Doctrine/DoctrinePostRepository.php');
        $repository2 = $this->finder->isFile($this->testModulePath . '/Repositories/Doctrine/DoctrineCategoryRepository.php');

        $this->assertTrue($repository);
        $this->assertTrue($repository2);
    }

    /** @test */
    public function it_should_generate_views()
    {
        $this->scaffoldModuleWithEloquent();

        $view1 = $this->finder->isFile($this->testModulePath . '/Resources/views/admin/posts/index.blade.php');
        $view2 = $this->finder->isFile($this->testModulePath . '/Resources/views/admin/posts/create.blade.php');
        $view3 = $this->finder->isFile($this->testModulePath . '/Resources/views/admin/posts/edit.blade.php');
        $view4 = $this->finder->isFile($this->testModulePath . '/Resources/views/admin/posts/partials/create-fields.blade.php');
        $view5 = $this->finder->isFile($this->testModulePath . '/Resources/views/admin/posts/partials/edit-fields.blade.php');

        $this->assertTrue($view1);
        $this->assertTrue($view2);
        $this->assertTrue($view3);
        $this->assertTrue($view4);
        $this->assertTrue($view5);
    }

    /** @test */
    public function it_should_generate_language_files()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $languageFile1 = $this->finder->isFile($this->testModulePath . '/Resources/lang/en/posts.php');
        $languageFile2 = $this->finder->isFile($this->testModulePath . '/Resources/lang/en/categories.php');

        $this->assertTrue($languageFile1);
        $this->assertTrue($languageFile2);
    }

    /** @test */
    public function it_should_generate_service_providers()
    {
        $this->scaffoldModuleWithEloquent();

        $file1 = $this->finder->isFile($this->testModulePath . '/Providers/RouteServiceProvider.php');
        $file2 = $this->finder->isFile($this->testModulePath . "/Providers/{$this->testModuleName}ServiceProvider.php");

        $this->assertTrue($file1);
        $this->assertTrue($file2);
    }

    /** @test */
    public function it_should_generate_controllers()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $file1 = $this->finder->isFile($this->testModulePath . '/Http/Controllers/Admin/PostController.php');
        $file2 = $this->finder->isFile($this->testModulePath . '/Http/Controllers/Admin/CategoryController.php');

        $this->assertTrue($file1);
        $this->assertTrue($file2);
    }

    /** @test */
    public function it_should_generate_routes_file()
    {
        $this->scaffoldModuleWithEloquent();

        $file1 = $this->finder->isFile($this->testModulePath . '/Http/routes.php');

        $this->assertTrue($file1);
    }
}
