<?php

namespace Rawebone\Ormish\Actions;

use Rawebone\Ormish\Utilities\EntityManager;
use Rawebone\Ormish\GatewayInterface;
use Rawebone\Ormish\Database;
use Rawebone\Ormish\Table;
use Rawebone\Ormish\Executor;
use Rawebone\Ormish\Utilities\Populater;
use Rawebone\Ormish\SqlGeneratorInterface;

/**
 * Provides a base for the Actions.
 */
abstract class AbstractAction
{
    /**
     * The Database that this action is connected to.
     *
     * @var \Rawebone\Ormish\Database
     */
    protected $database;
    
    /**
     * The Gateway this action is connected to.
     *
     * @var \Rawebone\Ormish\GatewayInterface
     */
    protected $gateway;
    
    /**
     * The Entity Manager this action is connected to.
     *
     * @var \Rawebone\Ormish\Utilities\EntityManager
     */
    protected $entityManager;
    
    /**
     * The Table this action is connected to.
     *
     * @var \Rawebone\Ormish\Table
     */
    protected $table;
    
    /**
     * The Executor this action is connected to.
     *
     * @var \Rawebone\Ormish\Executor
     */
    protected $executor;
    
    /**
     * The Populater this action is connection to.
     *
     * @var \Rawebone\Ormish\Populater
     */
    protected $populator;
    
    /**
     * The Generator that this action is connected to.
     *
     * @var \Rawebone\Ormish\SqlGeneratorInterface
     */
    protected $generator;
    
    public function __construct(Database $db, GatewayInterface $gw, EntityManager $em,
        Table $tbl, Executor $ex, Populater $pop, SqlGeneratorInterface $gen)
    {
        $this->database = $db;
        $this->gateway = $gw;
        $this->entityManager = $em;
        $this->table = $tbl;
        $this->executor = $ex;
        $this->populator = $pop;
        $this->generator = $gen;
    }
}
