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
        list($vendor, $name) = $this->askThemeName();

        $type = $this->choice('Would you like to create a front end or backend theme ?', ['Frontend', 'Backend'], 0);

        $this->themeScaffold->setName($name)->setVendor($vendor)->forType(strtolower($type))->generate();

        $this->info("Generated a fresh theme called [$name]. You'll find it in the Themes/ folder");
    }

    /**
     * Ask for the vendor and name, make sure it's the right format
     * @return string
     */
    private function askThemeName()
    {
        do {
            $themeName = $this->ask('Please enter the theme name in the following format: vendor/name');
            if ($themeName == '') {
                $this->command->error('Theme name is required in the following format: vendor/name');
            }
            $separatedVendorAndName = $this->separateVendorAndName($themeName);
        } while (! is_array($separatedVendorAndName));

        return $separatedVendorAndName;
    }

    /**
     * Extract the vendor and module name as two separate values
     * @param  string $fullName
     * @return array
     */
    private function separateVendorAndName($fullName)
    {
        $explodedFullName = explode('/', $fullName);

        return [
            $explodedFullName[0],
            ucfirst($explodedFullName[1]),
        ];
    }
}
