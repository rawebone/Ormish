<?php
namespace Rawebone\Ormish;

class Populator
{
    /**
     * Converts a result set to an array of classes.
     * 
     * @param \PDOStatement $stmt
     * @param string $className
     * @return array
     */
    public function populate(\PDOStatement $stmt, $className)
    {
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $className);
        $records = array();
        
        while (($record = $stmt->fetchObject())) {
            $records[] = $record;
        }
        
        return $records;
    }
}
