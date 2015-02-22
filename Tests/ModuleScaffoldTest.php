<?php namespace Modules\Workshop\Tests;

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

    protected $testbenchPath;

    public function setUp()
    {
        parent::setUp();
        $this->finder = $this->app['files'];
        $this->scaffold = $this->app['asgard.module.scaffold'];
        $this->testModuleName = 'TestingTestModule';
        $this->testbenchPath = __DIR__ . '/../vendor/orchestra/testbench/fixture/Modules/TestingTestModule';
        $this->testModulePath = $this->app->basePath() . "/Modules/{$this->testModuleName}";
    }

    /**
     *
     */
    private function cleanUp()
    {
        $this->finder->deleteDirectory($this->testModulePath);
        $this->finder->deleteDirectory($this->testbenchPath);
    }

    /**
     * Scaffold a test module using eloquent
     * @param array $entities
     * @param array $valueObjects
     */
    private function scaffoldModuleWithEloquent(array $entities = ['Post'], array $valueObjects = [])
    {
        $this->scaffoldModule('Eloquent', $entities, $valueObjects);
    }

    /**
     * Scaffold a test module using doctrine
     * @param array $entities
     * @param array $valueObjects
     */
    private function scaffoldModuleWithDoctrine(array $entities = ['Post'], array $valueObjects = [])
    {
        $this->scaffoldModule('Doctrine', $entities, $valueObjects);
    }

    /**
     * @param $type
     * @param $entities
     * @param $valueObjects
     * @throws \Modules\Workshop\Scaffold\Exception\ModuleExistsException
     */
    private function scaffoldModule($type, $entities, $valueObjects)
    {
        $this->scaffold
            ->vendor('asgardcms')
            ->name($this->testModuleName)
            ->setEntityType($type)
            ->withEntities($entities)
            ->withValueObjects($valueObjects)
            ->scaffold();

        $this->finder->copyDirectory($this->testbenchPath, $this->testModulePath);
        $this->finder->deleteDirectory($this->testbenchPath);
    }

    public function tearDown()
    {
        $this->cleanUp();
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

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_eloquent_repositories()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $repository = $this->finder->isFile($this->testModulePath . '/Repositories/Eloquent/EloquentPostRepository.php');
        $repository2 = $this->finder->isFile($this->testModulePath . '/Repositories/Eloquent/EloquentCategoryRepository.php');

        $this->assertTrue($repository);
        $this->assertTrue($repository2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_doctrine_repositories()
    {
        $this->scaffoldModuleWithDoctrine(['Post', 'Category']);

        $repository = $this->finder->isFile($this->testModulePath . '/Repositories/Doctrine/DoctrinePostRepository.php');
        $repository2 = $this->finder->isFile($this->testModulePath . '/Repositories/Doctrine/DoctrineCategoryRepository.php');

        $this->assertTrue($repository);
        $this->assertTrue($repository2);

        $this->cleanUp();
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

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_language_files()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $languageFile1 = $this->finder->isFile($this->testModulePath . '/Resources/lang/en/posts.php');
        $languageFile2 = $this->finder->isFile($this->testModulePath . '/Resources/lang/en/categories.php');

        $this->assertTrue($languageFile1);
        $this->assertTrue($languageFile2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_service_providers()
    {
        $this->scaffoldModuleWithEloquent();

        $file1 = $this->finder->isFile($this->testModulePath . '/Providers/RouteServiceProvider.php');
        $file2 = $this->finder->isFile($this->testModulePath . "/Providers/{$this->testModuleName}ServiceProvider.php");

        $this->assertTrue($file1);
        $this->assertTrue($file2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_controllers()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $file1 = $this->finder->isFile($this->testModulePath . '/Http/Controllers/Admin/PostController.php');
        $file2 = $this->finder->isFile($this->testModulePath . '/Http/Controllers/Admin/CategoryController.php');

        $this->assertTrue($file1);
        $this->assertTrue($file2);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_routes_file()
    {
        $this->scaffoldModuleWithEloquent();

        $file1 = $this->finder->isFile($this->testModulePath . '/Http/backendRoutes.php');

        $this->assertTrue($file1);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_sidebar_view_composer_file()
    {
        $this->scaffoldModuleWithEloquent();

        $file1 = $this->finder->isFile($this->testModulePath . '/Composers/SidebarViewComposer.php');

        $this->assertTrue($file1);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_permissions_config_file()
    {
        $this->scaffoldModuleWithEloquent();

        $file1 = $this->finder->isFile($this->testModulePath . '/Config/permissions.php');

        $this->assertTrue($file1);

        $this->cleanUp();
    }

    /** @test */
    public function it_should_generate_value_objects()
    {
        $this->scaffoldModuleWithEloquent(['Post'], ['Price', 'TimeRange']);

        $file1 = $this->finder->isFile($this->testModulePath . '/ValueObjects/Price.php');
        $file2 = $this->finder->isFile($this->testModulePath . '/ValueObjects/TimeRange.php');

        $this->assertTrue($file1);
        $this->assertTrue($file2);

        $this->cleanUp();
    }

    public function it_should_throw_exception_if_module_exists()
    {
        $this->setExpectedException('Modules\Workshop\Scaffold\Exception\ModuleExistsException');

        $this->scaffoldModuleWithEloquent();
        $this->scaffoldModuleWithEloquent();

        $this->assertEquals('Modules\Workshop\Scaffold\Exception\ModuleExistsException', $this->getExpectedException());
    }

    /** @test */
    public function it_should_generate_migrations_for_eloquent()
    {
        $this->scaffoldModuleWithEloquent(['Post', 'Category']);

        $migrations = $this->finder->allFiles($this->testModulePath . '/Database/Migrations');

        $this->assertCount(4, $migrations);
    }

    /** @test */
    public function it_should_generate_composer_json_file()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->finder->isFile($this->testModulePath . '/composer.json');

        $this->assertTrue($composerJson);
    }

    /** @test */
    public function it_should_change_the_type_to_asgard_module()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->finder->get($this->testModulePath . '/composer.json');
        $composerJson = json_decode($composerJson);

        $this->assertEquals('asgard-module', $composerJson->type);
    }

    /** @test */
    public function it_should_add_composers_installers_to_require_key()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->finder->get($this->testModulePath . '/composer.json');
        $composerJson = json_decode($composerJson);
        $key = 'composer/installers';

        $this->assertTrue(isset($composerJson->require->$key));
    }

    /** @test */
    public function it_should_add_require_dev_to_composer_json()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->finder->get($this->testModulePath . '/composer.json');
        $composerJson = json_decode($composerJson);
        $key = 'require-dev';

        $this->assertTrue(isset($composerJson->$key));
    }

    /** @test */
    public function it_should_add_autoload_dev_to_composer_json()
    {
        $this->scaffoldModuleWithEloquent();

        $composerJson = $this->finder->get($this->testModulePath . '/composer.json');
        $composerJson = json_decode($composerJson);
        $key = 'autoload-dev';

        $this->assertTrue(isset($composerJson->$key));
    }
}
