<?php
namespace Rawebone\Ormish;

/**
 * A Connector is used to generate queries for a particular database server.
 */
interface ConnectorInterface
{
    /**
     * Creates a new instance of the connector.
     * 
     * @param \PDO $conn The connection to encapsulate
     */
    function __construct(\PDO $conn);
    
    /**
     * Generates and executes an SQL query to find a record.
     * 
     * @return \PDOStatement
     */
    function find($table, $id, $is);
    
    /**
     * Generates and executes an SQL query to find records meeting the criteria.
     * 
     * @return \PDOStatement
     */
    function findWhere($table, $has);
    
    /**
     * Generates and executes a query to delete a record from a table. 
     * 
     * @return boolean
     */
    function delete($table, $id, $is, $soft = true);
    
    /**
     * Generates and executes a query to restore a record in a table.
     * 
     * @return boolean
     */
    function restore($table, $id, $is);
    
    /**
     * Generates and executes a query to insert a record into a table.
     * 
     * @return mixed The last insert ID
     */
    function insert($table, array $data);
    
    /**
     * Generates and executes a query to update a record in a table.
     * 
     * @return boolean
     */
    function update($table, array $data, $id, $is);
    
    /**
     * Returns the encapsulated connection object.
     * 
     * @return \PDO
     */
    function connection();
}
