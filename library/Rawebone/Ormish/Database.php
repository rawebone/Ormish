<?php

namespace Rawebone\Ormish;

use Rawebone\Ormish\Utilities\EntityManager;
use Rawebone\Ormish\Utilities\Populater;
use Rawebone\Ormish\Actions\ActionFactory;

class Database
{
    protected $executor;
    protected $factory;
    protected $entityManager;
    
    /**
     * @var \Rawebone\Ormish\GatewayInterface
     */
    protected $tables = array();

    public function __construct(Executor $exec, ActionFactory $factory, EntityManager $entityManager)
    {
        $this->executor = $exec;
        $this->factory = $factory;
        $this->entityManager = $entityManager;
    }

    /**
     * Attaches a table to the database for use.
     * 
     * @param string $entity
     * @return \Rawebone\Ormish\Database
     */
    public function attach($entity)
    {
        $table = $this->entityManager->table($entity);

        $gate = new Gateway(
                $this, 
                $table,
                $this->factory
        );
        $this->tables[$table->model()] = $gate;
        return true;
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
