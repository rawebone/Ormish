<?php

namespace Rawebone\Ormish\Utilities;

use phpDocumentor\Reflection\DocBlock;

class MetaDataManager
{
    protected $cache = array();
    
    public function metadata($class)
    {
        if (!isset($this->cache[$class])) {
            $this->addToCache($class);
        }
        
        return $this->cache[$class];
    }
    
    protected function addToCache($class)
    {
        $parser = new DocBlock(new \ReflectionClass($class));
        
        $map = array();
        foreach ($parser->getTagsByName("property") as $tag) {
            $map[str_replace("$", "", $tag->getVariableName())] = $tag->getType();
        }
        
        $this->cache[$class] = $map;
    }
}
