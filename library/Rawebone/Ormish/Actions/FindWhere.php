<?php

namespace Rawebone\Ormish\Actions;

use Rawebone\Ormish\Exceptions\ExecutionException;

class FindWhere extends AbstractAction
{
    public function run($condition)
    {
        $params = func_get_args();
        array_shift($params); // Remove $condition from the parameters list

        $query = $this->generator->findWhere($this->table->table(), $condition);

        try {
            $stmt = $this->executor->query($query, $params);
        } catch (ExecutionException $ex) { // Already logged
            return array();
        }

        $entities = $this->populator->populate($stmt, $this->table->model(), $this->table->id());
        foreach ($entities as $entity) {
            $this->entityManager->prepare(
                $entity,
                $this->gateway,
                $this->database,
                $this->table->readOnly()
            );
        }

        return $entities;
    }
}
