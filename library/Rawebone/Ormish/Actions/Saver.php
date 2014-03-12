<?php

namespace Rawebone\Ormish\Actions;

use Rawebone\Ormish\Entity;
use Rawebone\Ormish\Exceptions\ExecutionException;

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

        try {
            $entity->$id === null ? $this->tryInsert($id, $entity) : $this->tryUpdate($id, $entity);
            return true;
        } catch (ExecutionException $ex) { // Already logged
            return false;
        }
    }

    protected function tryInsert($id, Entity $entity)
    {
        $data = $this->uncastParams($entity->all());

        list($query, $params) = $this->generator->insert($this->table->table(), $data);
        $this->executor->exec($query, $params);

        $entity->$id = (int)$this->executor->lastInsertId();
    }

    protected function tryUpdate($id, Entity $entity)
    {
        $data = $this->uncastParams($entity->changes());

        list($query, $params) = $this->generator->update($this->table->table(), $data, $id, $entity->$id);

        $this->executor->exec($query, $params);
    }

    protected function uncastParams(array $data)
    {
        $map = $this->entityManager->properties($this->table->model());
        return $this->caster->toDbTypes($map, $data);
    }
}
