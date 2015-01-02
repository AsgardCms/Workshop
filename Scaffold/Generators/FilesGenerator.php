<?php namespace Modules\Workshop\Scaffold\Generators;

class FilesGenerator extends Generator
{
    /**
     * Generate the given files
     *
     * @param array $files
     * @return void
     */
    public function generate(array $files)
    {
        foreach ($files as $stub => $file) {
            $this->writeFile(
                $this->getModulesPath($file),
                $this->getContentFor($stub)
            );
        }
    }

    public function generateControllers()
    {
        $this->writeFile(
            $this->getModulesPath("Http/Controllers/Admin/{$this->name}Controller"),
            $this->getContentFor('admin-controller.stub')
        );

        return $this;
    }

    /**
     * Get the content for the given file
     *
     * @param $stub
     * @return string
     * @throws \Illuminate\Filesystem\FileNotFoundException
     */
    private function getContentFor($stub)
    {
        $stub = $this->finder->get($this->getStubPath($stub));

        return str_replace(
            ['$MODULE$', '$LOWERCASE_MODULE$', '$PLURAL_MODULE$'],
            [$this->name, strtolower($this->name), strtolower(str_plural($this->name))],
            $stub
        );
    }
}
