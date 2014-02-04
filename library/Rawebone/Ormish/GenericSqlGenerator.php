<?php

namespace Rawebone\Ormish;

class GenericSqlGenerator implements SqlGeneratorInterface
{
    public function convertDate(\DateTime $dt, $long = true)
    {
        return $dt->format("Y-m-d" . ($long ? " H:i:s" : ""));
    }

    public function delete($table, $id, $soft = true)
    {
        if ($soft) {
            return "UPDATE {$table} SET deleted = 1 WHERE {$id} = ?";
        } else {
            return "DELETE FROM {$table} WHERE {$id} = ?";
        }
    }

    public function find($table, $id)
    {
        return "SELECT * FROM $table WHERE deleted = 0 AND $id = ?";
    }

    public function findWhere($table, $has)
    {
        return "SELECT * FROM {$table} WHERE deleted = 0 AND {$has}";
    }

    public function insert($table, array $data)
    {
        list($fields, $placeholders, $values) = $this->buildInsertParts($data);
        
        $query = "INSERT INTO $table ($fields) VALUES($placeholders)";
        return array($query, $values);
    }

    public function restore($table, $id)
    {
        return "UPDATE {$table} SET deleted = 0 WHERE {$id} = ?";
    }

    public function update($table, array $data, $id, $is)
    {
        if (isset($data[$id])) {
            unset($data[$id]);
        }
        
        list($fields, $values) = $this->buildUpdateParts($data);
        
        $query = "UPDATE $table SET $fields WHERE $id = ?";
        $values[] = $is;
        
        return array($query, $values);
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
