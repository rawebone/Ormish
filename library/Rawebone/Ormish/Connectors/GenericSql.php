<?php
namespace Rawebone\Ormish\Connectors;

use PDO;
use Rawebone\Ormish\ConnectorInterface;

/**
 * GenericSql provides a standard way of reading from and updating an SQL
 * without vendor specific coding.
 */
class GenericSql implements ConnectorInterface
{
    protected $conn;
    
    public function __construct(PDO $conn)
    {
        $this->conn = $conn;
    }

    public function connection()
    {
        return $this->conn;
    }

    public function delete($table, $id, $is, $soft = true)
    {
        return $soft ? $this->softDelete($table, $id, $is) : $this->hardDelete($table, $id, $is);
    }

    public function find($table, $id, $is)
    {
        $query = "SELECT * FROM $table WHERE deleted = 0 AND $id = ?";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute(array($is));
        return $stmt;
    }

    public function findWhere($table, $has)
    {
        $query = "SELECT * FROM $table WHERE deleted = 0 AND $has";
        return $this->conn->query($query);
    }

    public function insert($table, array $data)
    {
        list($fields, $placeholders, $values) = $this->buildInsertParts($data);
        
        $query = "INSERT INTO $table ($fields) VALUES($placeholders)";
        $stmt  = $this->conn->prepare($query);
        $stmt->execute($values);
        return $this->conn->lastInsertId();
    }

    public function restore($table, $id, $is)
    {
        $query = "UPDATE $table SET deleted = 0 WHERE $id = ?";
        $stmt  = $this->conn->prepare($query);
        return $stmt->execute(array($is));
    }

    public function update($table, array $data, $id, $is)
    {
        if (isset($data[$id])) {
            unset($data[$id]);
        }
        
        list($fields, $values) = $this->buildUpdateParts($data);
        
        $query = "UPDATE $table SET $fields WHERE $id = ?";
        $values[] = $is;
        
        $stmt = $this->conn->prepare($query);
        return $stmt->execute($values);
    }
    
    protected function softDelete($table, $id, $is)
    {
        $query = "UPDATE $table SET deleted = 1 WHERE $id = ?";
        $stmt  = $this->conn->prepare($query);
        return $stmt->execute(array($is));
    }
    
    protected function hardDelete($table, $id, $is)
    {
        $query = "DELETE FROM $table WHERE $id = ?";
        $stmt  = $this->conn->prepare($query);
        return $stmt->execute(array($is));
    }
    
    protected function buildInsertParts(array $data)
    {
        $fields = "";
        $values = array();
        
        foreach ($data as $key => $value) {
            $values[] = $value;
            $fields .= "$key, ";
        }
        
        $placeholders = $this->trimListEnd(str_repeat("?, ", count($values)));
        return array($this->trimListEnd($fields), $placeholders, $values);
    }
    
    protected function buildUpdateParts(array $data)
    {
        $fields = "";
        $values = array();
        
        foreach ($data as $key => $value) {
            $values[] = $value;
            $fields .= "$key = ?, ";
        }
        
        return array($this->trimListEnd($fields), $values);
    }
    
    protected function trimListEnd($list)
    {
        return substr($list, 0, -2);
    }
}
