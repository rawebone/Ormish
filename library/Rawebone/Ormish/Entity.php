<?php
namespace Rawebone\Ormish;

abstract class Entity
{
    private $initialising = true;
    private $changes = array();
    
    /**
     * The container to be used when looking for related data.
     *
     * @var \Rawebone\Ormish\Container
     */
    protected $container;
    
    public function __construct(array $initial = array())
    {
        $this->modelApply($initial);
        $this->initialising = false;
    }
    
    /**
     * Get the value of a field in the data set, optionally via a method first.
     * 
     * @param string $name
     * @return mixed
     */
    public function __get($name)
    {
        $via = "get" . $this->modelGetFilter($name);
        return (method_exists($this, $via) ? $this->$via($this->$name) : $this->$name);
    }
    
    /**
     * Set the value of a field in the data set, optionally via a method first.
     * 
     * @param string $name
     * @param mixed $value
     * @return void 
     */
    public function __set($name, $value)
    {
        $via = "set" . $this->modelGetFilter($name);
        $this->$name = (method_exists($this, $via) ? $this->$via($value) : $value);
        
        $this->modelRecordChange($name, $this->$name);
    }

    /**
     * Used to call a relationship.
     * 
     * @param string $name
     * @param array $arguments
     * @return mixed
     */
    public function __call($name, $arguments)
    {
        $via = "relate" . $this->modelGetFilter($name);
        return (method_exists($this, $via) ? call_user_func_array(array($this, $via), $arguments) : null);
    }
    
    /**
     * Sets the orm instance that should be used for interacting with other
     * tables.
     * 
     * @param \Rawebone\Ormish\Container $container
     * @return void
     */
    public function modelContainer(Container $container)
    {
        $this->container = $container;
    }
    
    /**
     * Initialise values on the model.
     * 
     * @param array $values
     * @return void
     */
    public function modelApply(array $values)
    {
        foreach ($values as $key => $value) {
            $this->__set($key, $value);
        }
    }
    
    /**
     * Returns any values which have changed on the Model.
     * 
     * @return array
     */
    public function modelChanges()
    {
        return $this->changes;
    }
    
    /**
     * Resets the changes cache.
     * 
     * @return void
     */
    public function modelResetChanges()
    {
        $this->changes = array();
    }
    
    /**
     * Returns all of the data of the model.
     * 
     * @param boolean $raw Whether values should be filtered or not
     * @return array
     */
    public function modelAll($raw = true)
    {
        $rc   = new \ReflectionClass($this);
        $all  = array();
        $self = __NAMESPACE__ . "\\Entity";
        
        foreach ($rc->getProperties(\ReflectionProperty::IS_PROTECTED) as $prop) {
            if ($prop->class !== $self) {
                $name = $prop->getName();
                $all[$name] = $raw ? $this->$name : $this->__get($name);
            }
        }
        
        return $all;
    }
    
    /**
     * Whether the Model is initialising or not.
     * 
     * @return boolean
     */
    protected function modelIsInit()
    {
        return $this->initialising;
    }
    
    /**
     * Normalises a name into something appropriate for a method; 
     * i.e. hi_lloyd => HiLloyd, lloyd => Lloyd.
     * 
     * @param string $name
     * @return string
     */
    protected function modelGetFilter($name)
    {
        return str_replace(" ", "", ucwords(str_replace("_", " ", $name)));
    }
    
    /**
     * Records when a value of a property changes for differential updates.
     * 
     * @param string $name
     * @param mixed $new
     */
    protected function modelRecordChange($name, $new)
    {
        if (!$this->initialising && (!isset($this->changes[$name]) || $this->changes[$name] !== $new)) {
            $this->changes[$name] = $new;
        }
    }
}
