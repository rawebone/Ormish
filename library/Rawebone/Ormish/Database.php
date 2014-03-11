<?php

namespace Rawebone\Ormish;

use Rawebone\Ormish\Utilities\EntityManager;
use Rawebone\Ormish\Utilities\Populater;
use Rawebone\Ormish\Actions\ActionFactory;

class Database
{
    protected $executor;
    protected $factory;
    
    /**
     * @var \Rawebone\Ormish\GatewayInterface
     */
    protected $tables = array();

    public function __construct(Executor $exec, ActionFactory $factory)
    {
        $this->executor = $exec;
        $this->factory = $factory;
    }

    /**
     * "Magic" method, routes to get.
     * 
     * @see get()
     * @param string $name
     * @param array $args
     * @return \Rawebone\Ormish\GatewayInterface
     */
    public function __call($name, $args)
    {
        return $this->get($name);
    }
    
    /**
     * Attaches a table to the database for use.
     * 
     * @param \Rawebone\Ormish\Table $tbl
     * @return \Rawebone\Ormish\Database
     */
    public function attach(Table $tbl)
    {
        $gate = new Gateway(
                $this, 
                $tbl, 
                $this->factory
        );
        $this->tables[$tbl->table()] = $gate;
        return $this;
    }

    /**
     * Returns a gateway to a table.
     * 
     * @param string $table
     * @return \Rawebone\Ormish\GatewayInterface
     * @throws \Rawebone\Ormish\Exceptions\InvalidTableException
     */
    public function get($table)
    {
        if (!isset($this->tables[$table])) {
            throw new Exceptions\InvalidTableException($table);
        }
        
        return $this->tables[$table];
    }
    
    /**
     * Returns the executor currently in use.
     * 
     * @return \Rawebone\Ormish\Executor
     */
    public function getExecutor()
    {
        return $this->executor;
    }
}
