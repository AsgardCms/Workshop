<?php namespace Modules\Workshop\Scaffold\Generators;

class ValueObjectGenerator extends Generator
{
    /**
     * Generate the given files
     *
     * @param  array $valueObjects
     * @return void
     */
    public function generate(array $valueObjects)
    {
        foreach ($valueObjects as $valueObject) {
            $this->writeFile(
                $this->getModulesPath("ValueObjects/$valueObject"),
                $this->getContentForStub('value-object.stub', $valueObject)
            );
        }
    }
}
