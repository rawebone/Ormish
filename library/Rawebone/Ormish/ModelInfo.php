<?php
namespace Rawebone\Ormish;

/**
 * Encapsulates the information for a table and links that to a Model.
 */
class ModelInfo
{
    protected $model;
    protected $table;
    protected $primaryKey;
    protected $softDeletes;
    protected $noUpdates;
    
    public function __construct($model, $table, $primaryKey = "id", $softDeletes = true, $noUpdates = false)
    {
        $this->model = $model;
        $this->table = $table;
        $this->primaryKey = $primaryKey;
        $this->softDeletes = (boolean)$softDeletes;
        $this->noUpdates = (boolean)$noUpdates;
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
    
    public function noUpdates()
    {
        return $this->noUpdates;
    }
}
