<?php
namespace Rawebone\Ormish;

/**
 * Encapsulates the information for a table and links that to a Model.
 */
class Table
{
    protected $model;
    protected $table;
    protected $primaryKey;
    protected $softDeletes;
    protected $readOnly;
    
    public function __construct($model, $table, $primaryKey = "id", $softDeletes = true, $readOnly = false)
    {
        $this->model = $model;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->softDeletes = (boolean)$softDeletes;
        $this->readOnly = (boolean)$readOnly;
    }
    
    public function model()
    {
        return $this->model;
    }
    
    public function table()
    {
        return $this->table;
    }
    
    public function id()
    {
        return $this->primaryKey;
    }
    
    public function softDelete()
    {
        return $this->softDeletes;
    }
    
    public function readOnly()
    {
        return $this->readOnly;
    }
}
