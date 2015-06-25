<?php namespace Modules\Workshop\Tests;

use Modules\Workshop\Scaffold\Theme\Exceptions\FileTypeNotFoundException;
use Modules\Workshop\Scaffold\Theme\Exceptions\ThemeExistsException;
use Modules\Workshop\Scaffold\Theme\ThemeScaffold;

class ThemeScaffoldTest extends BaseTestCase
{
    public $path = 'Themes';

    /**
     * @var ThemeScaffold
     */
    protected $scaffold;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $finder;
    /**
     * @var
     */
    protected $testThemeName;
    /**
     * @var
     */
    protected $testThemePath;

    public function setUp()
    {
        parent::setUp();
        $this->finder = $this->app['files'];
        $this->scaffold = $this->app['asgard.theme.scaffold'];
        if (!$this->finder->isDirectory(base_path("Themes"))) {
            $this->finder->makeDirectory(base_path("Themes"));
        }
        $this->testThemeName = 'TestingTheme';
        $this->testThemePath = base_path("Themes/{$this->testThemeName}");
    }

    private function generateFrontendTheme()
    {
        $this->scaffold->setName($this->testThemeName)->forType('frontend')->generate();
    }

    public function tearDown()
    {
        $this->finder->deleteDirectory($this->testThemePath);
        $this->finder->deleteDirectory(base_path("Themes"));
    }

    /** @test */
    public function it_generates_theme_folder()
    {
        $this->scaffold->setFiles([]);

        $this->generateFrontendTheme();

        $this->assertTrue($this->finder->isDirectory($this->testThemePath));
    }

    /** @test */
    public function it_throws_exception_if_file_type_does_not_exist()
    {
        $this->setExpectedException(FileTypeNotFoundException::class);

        $this->generateFrontendTheme();
    }

    /** @test */
    public function it_throws_exception_if_theme_exists()
    {
        $this->setExpectedException(ThemeExistsException::class);

        $this->scaffold->setFiles([]);
        $this->generateFrontendTheme();
        $this->generateFrontendTheme();
    }

    /** @test */
    public function it_creates_theme_json_file()
    {
        $this->scaffold->setFiles(['themeJson']);

        $this->generateFrontendTheme();

        $this->assertTrue($this->finder->isFile($this->testThemePath . '/theme.json'));
    }
}
