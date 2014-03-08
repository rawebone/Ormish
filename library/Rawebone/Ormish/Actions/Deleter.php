<?php

namespace Rawebone\Ormish\Actions;

use Rawebone\Ormish\Entity;

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
        return $this->executor->exec($query, array($entity->$id));
    }
}
