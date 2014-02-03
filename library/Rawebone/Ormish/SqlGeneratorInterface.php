<?php
namespace Rawebone\Ormish;

/**
 * Implementers provide the ability to generate queries for a particular 
 * database server.
 */
interface SqlGeneratorInterface
{
    /**
     * Generates an SQL query to find a record by Primary Key.
     * 
     * @return string
     */
    function find($table, $id, $is);
    
    /**
     * Generates an SQL query to find records meeting the criteria.
     * 
     * @return string
     */
    function findWhere($table, $has, array $params = array());
    
    /**
     * Generates a query to delete a record from a table. 
     * 
     * @return string
     */
    function delete($table, $id, $is, $soft = true);
    
    /**
     * Generates a query to restore a record in a table.
     * 
     * @return string
     */
    function restore($table, $id, $is);
    
    /**
     * Generates a query to insert a record into a table.
     * 
     * @return string
     */
    function insert($table, array $data);
    
    /**
     * Generates a query to update a record in a table.
     * 
     * @return string
     */
    function update($table, array $data, $id, $is);
    
    /**
     * Returns a date in string format for the platform.
     * 
     * @return string
     */
    function convertDate(\DateTime $dt, $long = true);
}
