<?php

namespace Rawebone\Ormish\Utilities;

class DefaultsCreator
{
    public function make(array $types)
    {
        $map = array();
        foreach ($types as $field => $type) {
            switch ($type) {
                case "int":
                    $value = 0;
                    break;
                case "null":
                    $value = null;
                    break;
                case "bool":
                case "boolean":
                    $value = false;
                    break;
                case "DateTime":
                    $value = new \DateTime();
                    break;
                default:
                    $value = "";
            }
            
            $map[$field] = $value;
        }
        return $map;
    }
}
