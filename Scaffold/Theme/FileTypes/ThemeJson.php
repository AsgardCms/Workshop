<?php namespace Modules\Workshop\Scaffold\Theme\FileTypes;

use Modules\Workshop\Scaffold\Theme\Traits\FindsThemePath;

class ThemeJson implements FileType
{
    use FindsThemePath;

    /**
     * @var \Illuminate\Filesystem\Filesystem
     */
    protected $finder;
    /**
     * @var array
     */
    private $options;

    public function __construct(array $options)
    {
        $this->finder = app('Illuminate\Filesystem\Filesystem');
        $this->options = $options;
    }

    /**
     * Generate the current file type
     * @return string
     */
    public function generate()
    {
        $stub = $this->finder->get(__DIR__ . '/../stubs/themeJson.stub');

        $stub = $this->replaceContentInStub($stub);

        $this->finder->put($this->themePathForFile($this->options['name'], 'theme.json'), $stub);
    }

    public function replaceContentInStub($stub)
    {
        return str_replace(
            [
                '{{theme-name}}',
                '{{type}}',
            ],
            [
                $this->options['name'],
                $this->options['type'],
            ],
            $stub
        );
    }
}
