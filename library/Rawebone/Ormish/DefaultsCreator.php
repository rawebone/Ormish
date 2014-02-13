<?php

namespace Rawebone\Ormish;

class DefaultsCreator
{
    public function make(array $types)
    {
        $map = array();
        foreach ($types as $field => $type) {
            switch ($field) {
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
                default:
                    $value = "";
            }
            
            $map[$field] = $value;
        }
        return $map;
    }
}
