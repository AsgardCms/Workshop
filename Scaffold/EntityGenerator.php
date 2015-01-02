<?php namespace Modules\Workshop\Scaffold;

class EntityGenerator extends Generator
{
    /**
     * Generate the given entities
     * @param array $entities
     */
    public function generate(array $entities)
    {
        foreach ($entities as $entity) {
            $this->writeFile(
                $this->getModulesPath("Entities/$entity"),
                $this->getContentFor($entity)
            );
        }
    }

    /**
     * Get the content for the given entity
     * @param $entity
     * @return void
     * @throws \Illuminate\Filesystem\FileNotFoundException
     */
    private function getContentFor($entity)
    {
        $stub = $this->finder->get($this->getStubPath('entity.stub'));

        return str_replace(
            ['$MODULE$', '$NAME$', '$PLURALNAME$'],
            [$this->name, $entity, strtolower(str_plural($entity))],
            $stub
        );
    }
}
