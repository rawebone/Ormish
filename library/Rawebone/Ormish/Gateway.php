<?php

namespace Rawebone\Ormish;

class Gateway implements GatewayInterface
{
    protected $database;
    protected $table;
    protected $generator;
    protected $executor;
    protected $populator;
    protected $entityManager;
    
    public function __construct(Database $db, Table $tbl, 
        SqlGeneratorInterface $gen, Executor $exec, Populator $pop, EntityManager $em)
    {
        $this->database = $db;
        $this->table = $tbl;
        $this->generator = $gen;
        $this->executor = $exec;
        $this->populator = $pop;
        $this->entityManager = $em;
    }

    public function create(array $initial = array())
    {
        $name = $this->table->model();
        $id   = $this->table->id();
        $ro   = $this->table->readOnly();
        $em   = $this->entityManager;
        $db   = $this->database;
        
        return $em->prepare($em->create($name, $id, $initial), $this, $db, $ro); 
    }

    public function delete(Entity $entity)
    {
        $tbl = $this->table;
        if ($tbl->readOnly()) {
            return false;
        }
        
        $id = $tbl->id();
        $query = $this->generator->delete($tbl->table(), $id, $tbl->softDelete());
        
        return $this->executor->exec($query, array($entity->$id));
    }

    public function find($id)
    {
        $query = $this->generator->find($this->table->table(), $this->table->id());
        $stmt  = $this->executor->query($query, array($id));
        
        if ($stmt instanceof Error) {
            return $stmt;
        }
        
        $ents  = $this->populator->populate($stmt, $this->table->model());
        
        if (!isset($ents[0])) {
            return null;
        } else {
            $ent = $ents[0];
            $this->entityManager->prepare(
                    $ent, 
                    $this, 
                    $this->database, 
                    $this->table->readOnly()
            );
            return $ent;
        }
    }

    public function findOneWhere($conditions)
    {
        $rows = call_user_func_array(array($this, "findWhere"), func_get_args());
        if ($rows instanceof Error) {
            return $rows;
        }
        
        return isset($rows[0]) ? $rows[0] : null;
    }

    public function findWhere($condition)
    {
        $params = func_get_args();
        array_shift($params); // Clear $condition
        
        $query = $this->generator->findWhere($this->table->table(), $condition);
        $stmt  = $this->executor->query($query, $params);
        
        if ($stmt instanceof Error) {
            return $stmt;
        }
        
        $rows = $this->populator->populate($stmt, $this->table->model());
        foreach ($rows as $row) {
            $this->entityManager->prepare($row, $this, $this->database, $this->table->readOnly());
        }
        
        return $rows;
    }

    public function save(Entity $entity)
    {
        if ($this->table->readOnly()) {
            return false;
        }
        
        $id = $this->table->id();
        
        return ($entity->$id === null ? $this->tryInsert($entity) : $this->tryUpdate($entity));
    }
    
    protected function tryInsert(Entity $entity)
    {
        $id = $this->table->id();
        
        list($query, $params) = $this->generator->insert($this->table->table(), $entity->all());
        if ($this->executor->exec($query, $params)) {
            $entity->$id = (int)$this->executor->lastInsertId();
            return true;
        } else {
            return false;
        }
    }
    
    protected function tryUpdate(Entity $entity)
    {
        $id = $this->table->id();
        list($query, $params) = $this->generator->update($this->table->table(), $entity->all(), $id, $entity->$id);
        return $this->executor->exec($query, $params);
    }
}
