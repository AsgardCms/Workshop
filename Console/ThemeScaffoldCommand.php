<?php namespace Modules\Workshop\Console;

use Illuminate\Console\Command;
use Modules\Workshop\Scaffold\Theme\ThemeScaffold;

class ThemeScaffoldCommand extends Command
{
    protected $signature = 'asgard:theme:scaffold';
    protected $description = 'Scaffold a new theme';
    /**
     * @var ThemeScaffold
     */
    private $themeScaffold;

    public function __construct(ThemeScaffold $themeScaffold)
    {
        parent::__construct();
        $this->themeScaffold = $themeScaffold;
    }

    public function fire()
    {
        $themeName = $this->argument('name');
        $this->info("Generating a fresh theme called [$themeName] ...");
    }
}
