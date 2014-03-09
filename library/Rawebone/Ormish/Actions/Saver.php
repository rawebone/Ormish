<?php

namespace Rawebone\Ormish\Actions;

use Rawebone\Ormish\Entity;

/**
 * Saver performs operations to Insert or Update Entities in the database.
 */
class Saver extends AbstractAction
{
    public function run(Entity $entity)
    {
        if ($this->table->readOnly()) {
            return false;
        }

        $id = $this->table->id();

        return $entity->$id === null ? $this->tryInsert($entity) : $this->tryUpdate($entity);
    }

    protected function tryInsert(Entity $entity)
    {
        list($query, $params) = $this->generator->insert($this->table->table(), $entity->all());
        if ($this->executor->exec($query, $params)) {
            $id = $this->table->id();
            $entity->$id = (int)$this->executor->lastInsertId();
            return true;
        } else {
            return false;
        }
    }

    protected function tryUpdate(Entity $entity)
    {
        $id = $this->table->id();

        list($query, $params) = $this->generator->update($this->table->table(), $entity->changes(), $id, $entity->$id);

        return $this->executor->exec($query, $params);
    }
}
