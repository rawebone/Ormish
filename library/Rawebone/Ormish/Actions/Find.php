<?php

namespace Rawebone\Ormish\Actions;

use Rawebone\Ormish\Exceptions\ExecutionException;

/**
 * Find's a record in a table by Primary Key
 */
class Find extends AbstractAction
{
    public function run($id)
    {
        $query = $this->generator->find($this->table->table(), $this->table->id());

        try {
            $stmt = $this->executor->query($query, array($id));
        } catch (ExecutionException $ex) { // Already logged
            return null;
        }

        $entities = $this->populator->populate($stmt, $this->table->model(), $this->table->id());

        if (count($entities) !== 1) {
            return null;
        }

        $entity = $entities[0];
        $this->entityManager->prepare($entity, $this->gateway, $this->database, $this->table->readOnly());
        return $entity;
    }
}
