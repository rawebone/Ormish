<?php

namespace Rawebone\Ormish\Actions;

class FindOneWhere extends FindWhere
{
    public function run($condition)
    {
        $entities = call_user_func_array(array("parent", "run"), func_get_args());

        if (count($entities) === 0) {
            return null;
        }

        return $entities[0];
    }
}
