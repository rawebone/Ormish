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
        $records = array();
        
        while (($record = $stmt->fetch(\PDO::FETCH_ASSOC))) {
            $records[] = new $className($record);
        }
        
        return $records;
    }
}
