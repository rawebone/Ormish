<?php
namespace Rawebone\Ormish;

class Container
{
    protected $connector;
    protected $populator;
    protected $models = array();
    
    public function __construct(ConnectorInterface $connector, Populator $populator = null)
    {
        $this->connector = $connector;
        $this->populator = $populator ?: new Populator();
    }
    
    public function __call($name, $arguments)
    {
        if (!isset($this->models[$name])) {
            throw new InvalidTableException($name);
        }
        
        return $this->models[$name];
    }
    
    public function attach(ModelInfo $info)
    {
        $this->models[$info->table()] = new Gateway($this->connector, $this->populator, $info, $this);
    }
}
