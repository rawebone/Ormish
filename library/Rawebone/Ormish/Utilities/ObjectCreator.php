<?php

namespace Rawebone\Ormish\Utilities;

/**
 * ObjectCreator handles instantiating objects by name via reflection, 
 * allowing for dynamic invokation without the chance of Fatal Errors. No
 * handling for ReflectionExceptions are provided.
 */
class ObjectCreator
{
    protected $reflections = array();

    public function create($name, array $arguments = array(), $unpack = true)
    {
        $rc = $this->getReflection($name);

        return ($unpack ? $rc->newInstanceArgs($arguments) : $rc->newInstance($arguments));
    }
    
    /**
     * Returns an instance of a ReflectionClass for invokation. This instance
     * will be cached for the future.
     * 
     * @param string $name
     * @return \ReflectionClass
     */
    protected function getReflection($name)
    {
        if (!isset($this->reflections[$name])) {
            $this->reflections[$name] = new \ReflectionClass($name);
        }
        
        return $this->reflections[$name];
    }
}
