<?php
namespace Rawebone\Ormish;

class Gateway implements GatewayInterface
{
    protected $connector;
    protected $info;
    protected $pop;
    protected $container;
    
    public function __construct(ConnectorInterface $connector, Populator $pop, ModelInfo $info, Container $orm)
    {
        $this->connector = $connector;
        $this->info = $info;
        $this->pop = $pop;
        $this->container = $orm;
    }

    public function delete(Entity $entity)
    {
        $info = $this->info;
        $id   = $info->id();
        return $this->connector->delete($info->table(), $id, $entity->$id, $info->softDelete());
    }

    public function find($id)
    {
        $info = $this->info;
        $stmt = $this->connector->find($info->table(), $info->id(), $id);
        $rows = $this->pop->populate($stmt, $info->model());
        
        if (isset($rows[0])) {
            return $this->prepareEntity($rows[0]);
        } else {
            return null;
        }
    }
    
    public function findWhere($condition)
    {
        $info = $this->info;
        $stmt = $this->connector->findWhere($info->table(), $condition);
        $rows = $this->pop->populate($stmt, $info->model());

        foreach ($rows as $row) {
            $this->prepareEntity($row);
        }
        
        return $rows;
    }

    public function save(Entity $entity)
    {
        $info = $this->info;
        $id   = $info->id();
        
        if (!$entity->$id) {
            return $this->connector->insert($info->table(), $entity->modelAll(true));
        } else if (!$info->noUpdates()) {
            return $this->connector->update($info->table(), $entity->modelChanges(), $id, $entity->$id);
        } else {
            return false;
        }
    }
    
    public function create(array $initial = array())
    {
        $name = $this->info->model();
        return new $name($initial);
    }
    
    /**
     * Prepares an entity for use in the system.
     * 
     * @param \Rawebone\Ormish\Entity $entity
     * @return \Rawebone\Ormish\Entity
     */
    protected function prepareEntity(Entity $entity)
    {
        $entity->modelContainer($this->container);
        $entity->modelGateway($this);
        return $entity;
    }
}
