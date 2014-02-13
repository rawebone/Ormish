<?php

namespace Rawebone\Ormish;

class Database
{
    protected $executor;
    protected $generator;
    protected $populator;
    
    /**
     * @var \Rawebone\Ormish\GatewayInterface
     */
    protected $tables = array();

    public function __construct(Executor $exec, SqlGeneratorInterface $gen, Populator $pop)
    {
        $this->executor = $exec;
        $this->generator = $gen;
        $this->populator = $pop;
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
        $gate = new Gateway($this, $tbl, $this->generator, $this->executor, $this->populator);
        $this->tables[$tbl->table()] = $gate;
        return $this;
    }

    /**
     * Returns a gateway to a table.
     * 
     * @param string $table
     * @return \Rawebone\Ormish\GatewayInterface
     * @throws InvalidTableException
     */
    public function get($table)
    {
        if (!isset($this->tables[$table])) {
            throw new InvalidTableException($table);
        }
        
        return $this->tables[$table];
    }
    
    /**
     * Returns the executor currently in use.
     * 
     * @return \Rawebone\Ormish\Executor
     */
    public function getExector()
    {
        return $this->executor;
    }
}
