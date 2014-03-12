<?php

namespace Rawebone\Ormish;

use Rawebone\Ormish\Utilities\Shadow;

/**
 * Entity is the core of Ormish, providing a simplistic interface for creating
 * smart Domain Objects.
 */
class Entity
{
    /**
     * @var \Rawebone\Ormish\Database
     */
    private static $globalDatabase;

    /**
     * @var array
     */
    private $values;
    
    /**
     * The shadow to be used to track changes.
     *
     * @var \Rawebone\Ormish\Shadow
     */
    private $shadow;
    
    /**
     * The gateway to be used to update the entity in the database.
     *
     * @var \Rawebone\Ormish\GatewayInterface
     */
    private $gateway;
    
    /**
     * The database that the entity belongs to.
     *
     * @var \Rawebone\Ormish\Database
     */
    private $database;

    public function __construct(array $initial = array())
    {
        $defaults = array(
            "deleted" => 0,
        );
        
        $this->values = array_merge($defaults, $initial);
    }
    
    public function __get($name)
    {
        return isset($this->values[$name]) ? $this->values[$name] : null;
    }
    
    public function __set($name, $value)
    {
        $this->values[$name] = $value;
    }

    public function letShadow(Shadow $shadow)
    {
        $this->shadow = $shadow;
    }

    public function letDatabase(Database $database)
    {
        $this->database = $database;
    }

    public function letGateway(GatewayInterface $gateway)
    {
        $this->gateway = $gateway;
    }

    public function save()
    {
        if (!$this->gateway->save($this)) {
            return false;
        }
        
        $this->shadow->update($this->values);
        return true;
    }

    public function delete()
    {
        if (!$this->gateway->delete($this)) {
            return false;
        }
        
        $this->__set("deleted", 1);
        $this->shadow->update($this->values);
        return true;
    }
    
    public function all()
    {
        return $this->values;
    }
    
    public function changes()
    {
        return $this->shadow->changes($this->values);
    }
    
    public function hasChanged()
    {
        return count($this->shadow->changes($this->values)) > 0;
    }
    
    /**
     * @return \Rawebone\Ormish\Database
     */
    protected function getDatabase()
    {
        return $this->database;
    }

    /**
     * Allows for using the system as per other ORM's.
     *
     * @param string $name
     * @param array $arguments
     * @return mixed
     * @throws \ErrorException
     */
    public static function __callStatic($name, $arguments)
    {
        if (self::$globalDatabase === null) {
            $msg = "Call to static interface made, global database not registered";
            throw new \ErrorException($msg);
        }

        $gateway = self::$globalDatabase->get(get_called_class());

        $callable = array($gateway, $name);
        return call_user_func_array($callable, $arguments);
    }

    /**
     * Allows for using the system as per other ORM's.
     *
     * @param \Rawebone\Ormish\Database $db
     */
    public static function globalDatabase(Database $db)
    {
        self::$globalDatabase = $db;
    }
}
