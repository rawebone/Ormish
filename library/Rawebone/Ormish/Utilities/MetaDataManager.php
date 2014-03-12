<?php

namespace Rawebone\Ormish\Utilities;

use phpDocumentor\Reflection\DocBlock;

class MetaDataManager
{
    protected $docBlockCache = array();
    protected $propertyCache = array();
    protected $cache = array();
    
    public function properties($class)
    {
        if (!isset($this->propertyCache[$class])) {
            $this->cacheProperties($class);
        }
        
        return $this->propertyCache[$class];
    }
    
    protected function cacheProperties($class)
    {
        $parser = $this->getDocBlock($class);
        
        $map = array();
        foreach ($parser->getTagsByName("property") as $tag) {
            $type = $tag->getType();
            while ($type[0] === '\\') {
                $type = substr($type, 1);
            }

            $map[str_replace("$", "", $tag->getVariableName())] = $type;
        }
        
        $this->propertyCache[$class] = $map;
    }

    /**
     * @param string $class The class name to get the parser for.
     * @return \phpDocumentor\Reflection\DocBlock
     */
    protected function getDocBlock($class)
    {
        if (!isset($this->docBlockCache[$class])) {
            $this->docBlockCache[$class] = new DocBlock(new \ReflectionClass($class));
        }

        return $this->docBlockCache[$class];
    }
}
