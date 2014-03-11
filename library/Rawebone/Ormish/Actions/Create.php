<?php

namespace Rawebone\Ormish\Actions;

class Create extends AbstractAction
{
    public function run(array $initial)
    {
        $name = $this->table->model();
        $id   = $this->table->id();
        $ro   = $this->table->readOnly();
        $em   = $this->entityManager;
        $db   = $this->database;
        $gw   = $this->gateway;

        $ent = $em->create($name, $id, $initial);
        $em->prepare($ent, $gw, $db, $ro);

        return $ent;
    }
}
