<?php namespace Modules\Workshop\Scaffold\Generators;

class ValueObjectGenerator extends Generator
{
    /**
     * Generate the given files
     *
     * @param array $valueObjects
     * @return void
     */
    public function generate(array $valueObjects)
    {
        foreach ($valueObjects as $valueObject) {
            $this->writeFile(
                $this->getModulesPath("ValueObjects/$valueObject"),
                $this->getContentFor($valueObject)
            );
        }
    }

    /**
     * Get the content for the given entity
     *
     * @param $valueObject
     * @return string
     * @throws \Illuminate\Filesystem\FileNotFoundException
     */
    private function getContentFor($valueObject)
    {
        $stub = $this->finder->get($this->getStubPath('value-object.stub'));

        return str_replace(
            ['$MODULE$', '$NAME$'],
            [$this->name, $valueObject],
            $stub
        );
    }
}
