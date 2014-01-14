<?php
namespace Rawebone\Ormish;

/**
 * Provides an abstraction between a model and a database.
 */
interface GatewayInterface
{
    /**
     * Creates the gateway.
     */
    function __construct(ConnectorInterface $connector, Populator $pop, ModelInfo $info, Container $orm);

    /**
     * Finds a record on the gateway by $id.
     * 
     * @param string $id
     * @return \Rawebone\Ormish\Entity|null
     */
    function find($id);
    
    /**
     * Returns records where a condition is met on the table.
     * 
     * @return array|\Rawebone\Ormish\Entity
     */
    function findWhere($condition);
    
    /**
     * Saves a record on the gateway.
     * 
     * @param \Rawebone\Ormish\Entity
     * @return boolean
     */
    function save(Entity $entity);
    
    /**
     * Deletes a record on the gateway.
     * 
     * @param \Rawebone\Ormish\Entity
     * @return boolean
     */
    function delete(Entity $entity);
}
