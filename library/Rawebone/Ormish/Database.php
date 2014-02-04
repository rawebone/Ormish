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

    public function attach(Table $tbl)
    {
        $gate = new Gateway($this, $tbl, $this->generator, $this->executor, $this->populator);
        $this->tables[$tbl->table()] = $gate;
    }

    public function get($table)
    {
        if (!isset($this->tables[$table])) {
            throw new InvalidTableException($table);
        }
        
        return $this->tables[$table];
    }
}
