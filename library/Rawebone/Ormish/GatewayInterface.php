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
     * Returns records where a condition is met on the table. Any additional
     * arguments will be passed as parameters for the query. I.e.:
     * 
     * <code>
     * $gateway->findWhere("a = ?", 1);
     * </code>
     * 
     * @return array|\Rawebone\Ormish\Entity
     */
    function findWhere($condition);
    
    /**
     * Returns a single record where a condition is met on the table. Calls
     * findWhere internally and shares the same signature.
     * 
     * @see findWhere
     * @return \Rawebone\Ormish\Entity
     */
    function findOneWhere($conditions);
    
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
    
    /**
     * Creates a new instance of the Model for the table.
     * 
     * @return \Rawebone\Ormish\Entity
     */
    function create(array $initial = array());
}
