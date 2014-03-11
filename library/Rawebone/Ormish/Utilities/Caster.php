<?php

namespace Rawebone\Ormish\Utilities;

/**
 * Provides a mechanism from converting strings from the database into
 * the appropriate PHP Types and back.
 */
class Caster
{
    public function toPhpTypes(array $map, array $values)
    {
        $casted = array();

        foreach ($values as $key => $value) {

            $cast = null;

            switch ($map[$key]) {
                case "int":
                case "integer":
                    $cast = (int)$value;
                    break;

                case "float":
                case "double":
                    $cast = floatval($value);
                    break;

                case "bool":
                case "boolean":
                    $cast = (bool)$value;
                    break;

                case "DateTime":
                    $cast = new \DateTime($value);
                    break;

                default:
                    $cast = $value;
            }

            $casted[$key] = $cast;
        }

        return $casted;
    }

    public function toDbTypes(array $map, array $values)
    {
        $casted = array();

        foreach ($values as $key => $value) {
            $cast = null;
            switch($map[$key]) {
                case "bool":
                    $cast = ($value ? "1" : "0");
                    break;
                case "DateTime":
                    $cast = $value->format("Y-m-d H:i:s");
                    break;
                default:
                    $cast = (string)$value;
            }

            $casted[$key] = $cast;
        }

        return $casted;
    }
}
