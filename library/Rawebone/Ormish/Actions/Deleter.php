<?php

namespace Rawebone\Ormish\Actions;

use Rawebone\Ormish\Entity;
use Rawebone\Ormish\Exceptions\ExecutionException;

/**
 * Handles deleting an Entity from the database.
 */
class Deleter extends AbstractAction
{
    public function run(Entity $entity)
    {
        if ($this->table->readOnly()) {
            return false;
        }
        
        $id   = $this->table->id();
        $soft = $this->table->softDelete();
        $tbl  = $this->table->table();
        
        $query = $this->generator->delete($tbl, $id, $soft);

        try {
            $this->executor->exec($query, array($entity->$id));
            return true;
        } catch (ExecutionException $ex) { // Already logged
            return false;
        }
    }
}
