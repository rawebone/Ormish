<?php

namespace Rawebone\Ormish;

class Gateway implements GatewayInterface
{
    protected $database;
    protected $table;
    protected $generator;
    protected $executor;
    protected $populator;
    
    public function __construct(Database $db, Table $tbl, 
        SqlGeneratorInterface $gen, Executor $exec, Populator $pop)
    {
        $this->database = $db;
        $this->table = $tbl;
        $this->generator = $gen;
        $this->executor = $exec;
        $this->populator = $pop;
    }

    public function create(array $initial = array())
    {
        $name = $this->table->model();
        return $this->prepareEntity(new $name($initial));
    }

    public function delete(Entity $entity)
    {
        if ($this->table->readOnly()) {
            return false;
        }
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
            return $this->prepareEntity($ents[0]);
        }
    }

    public function findOneWhere($conditions)
    {
        $rows = call_user_func_array(array($this, "findWhere"), func_get_args());
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
            $this->prepareEntity($row);
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
        list($query, $params) = $this->generator->insert($this->table->table(), $entity->all());
        return $this->executor->exec($query, $params);
    }
    
    protected function tryUpdate(Entity $entity)
    {
        $id = $this->table->id();
        list($query, $params) = $this->generator->update($this->table->table(), $entity->all(), $id, $entity->$id);
        return $this->executor->exec($query, $params);
    }
    
    /**
     * Prepares an entity for use in the system.
     * 
     * @param \Rawebone\Ormish\Entity $entity
     * @return \Rawebone\Ormish\Entity
     */
    protected function prepareEntity(Entity $entity)
    {
        $shadow = $this->table->readOnly() ? new NullShadow() : new Shadow();
        $shadow->update($entity->all());
        
        $entity->letDatabase($this->database);
        $entity->letShadow($shadow);
        $entity->letGateway($this);
        
        return $entity;
    }
}
