<?php

namespace Rawebone\Ormish\Utilities;

/**
 * Returns MetaData about classes use in the library.
 *
 * We could fairly easily use one of the libraries designed
 * to parse for annotations - however given the relative
 * simplicity of what we want to achieve the overhead
 * and complexity caused is unnecessary. As such handwritten,
 * specific parsers are the way forward here.
 */
class MetaDataManager
{
    protected $docBlockCache = array();
    protected $propertyCache = array();
    protected $tableCache    = array();

    public function properties($class)
    {
        if (!isset($this->propertyCache[$class])) {
            $comment = $this->getDocBlock($class);
            $properties = $this->parseDocBlockProperties($comment);

            $this->propertyCache[$class] = $properties;
        }
        
        return $this->propertyCache[$class];
    }

    public function table($class)
    {
        if (!isset($this->tableCache[$class])) {
            $comment = $this->getDocBlock($class);
            $table   = $this->parseDocBlockForTable($comment);

            $this->tableCache[$class] = $table;
        }

        return $this->tableCache[$class];
    }

    /**
     * Returns the `property` tags from a DocBlock in the format
     * <type, name>,
     *
     * @param $comment
     * @return array
     */
    protected function parseDocBlockProperties($comment)
    {
        static $regex = "#@property (?<type>[a-zA-Z0-9\\\]+) \\$(?<name>[_a-zA-Z0-9]+)#";

        if (empty($comment)
            || ($count = preg_match_all($regex, $comment, $matches)) === 0) {
            return array();
        }

        $map = array();
        for ($i = 0; $i < $count; $i++) {
            $type = $matches["type"][$i];
            $name = $matches["name"][$i];

            while ($type[0] === '\\') {
                $type = substr($type, 1);
            }

            $map[$name] = $type;
        }

        return $map;
    }

    /**
     * Returns the details of the table annotations.
     *
     * @param string $comment
     * @return array
     */
    protected function parseDocBlockForTable($comment)
    {
        if (empty($comment)) {
            return array();
        }

        $table = array(
            "table" => $this->getValueForTag("table", $comment),
            "primaryKey" => $this->getValueForTag("primaryKey", $comment),
            "softDelete" => (preg_match("#@softDelete#", $comment) === 1),
            "readOnly" => (preg_match("#@softDelete#", $comment) === 1)
        );

        return $table;
    }

    /**
     * Returns the string from the DocComment of the class.
     *
     * @param string $class The class name to get the parser for.
     * @return string
     */
    protected function getDocBlock($class)
    {
        if (!isset($this->docBlockCache[$class])) {
            $reflection = new \ReflectionClass($class);
            $this->docBlockCache[$class] = $reflection->getDocComment();
        }

        return $this->docBlockCache[$class];
    }

    /**
     * Where we expect to have a tag followed by a string and a new line,
     * return that string as the value for the tag.
     *
     * @param string $name
     * @param string $comment
     */
    protected function getValueForTag($name, $comment)
    {
        $regex = "#@{$name} (?<value>[_a-zA-Z0-9]+)#";

        if (preg_match($regex, $comment, $match) == 0) {
            return null;
        }

        return $match["value"];
    }
}
