<?php

namespace Rawebone\Ormish;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;
use Rawebone\Ormish\Actions\ActionFactory;
use Rawebone\Ormish\Utilities\Caster;
use Rawebone\Ormish\Utilities\DefaultsCreator;
use Rawebone\Ormish\Utilities\MetaDataManager;
use Rawebone\Ormish\Utilities\ObjectCreator;
use Rawebone\Ormish\Utilities\EntityManager;
use Rawebone\Ormish\Utilities\Populater;

class Factory
{
    protected $dsn;
    protected $username;
    protected $password;
    protected $options;
    protected $log;
    protected $gen;
    protected $pop;
    protected $em;
    protected $objects;
    protected $caster;
    protected $execClass;
    protected $dbClass;

    public function __construct($dsn, $username, $password, array $options = array())
    {
        $this->dsn      = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options  = $options;
        $this->objects  = new ObjectCreator();
        $this->caster   = new Caster();
        $this->em       = new EntityManager(new DefaultsCreator(), new MetaDataManager(), $this->objects);
        $this->pop      = new Populater($this->caster, $this->em);
        
        // Default objects and settings which can be overridden
        $this->log = new NullLogger();
        $this->gen = new GenericSqlGenerator();
        $this->dbClass = __NAMESPACE__ . '\Database';
        $this->execClass = __NAMESPACE__ . '\Executor';
    }

    /**
     * Returns the configured database layer.
     * 
     * @return \Rawebone\Ormish\Database
     */
    public function build()
    {
        $pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
        
        $exec = $this->objects->create($this->execClass, array($pdo, $this->log));
        $factory = new ActionFactory($this->em, $exec, $this->pop, $this->gen, $this->objects);
        return $this->objects->create($this->dbClass, array($exec, $factory));
    }
    
    /**
     * Returns the instance of the logger that will be used in the database
     * layer.
     * 
     * @return \Psr\Log\LoggerInterface
     */
    public function logger()
    {
        return $this->log;
    }

    /**
     * Returns the instance of the SQL Generator that will be used in the 
     * database layer.
     * 
     * @return \Rawebone\Ormish\SqlGeneratorInterface
     */
    public function generator()
    {
        return $this->gen;
    }

    /**
     * Sets the instance of the logger that will be used in the database layer.
     * 
     * @param \Psr\Log\LoggerInterface $log
     */
    public function setLogger(LoggerInterface $log)
    {
        $this->log = $log;
    }

    /**
     * Sets the instance of the SQL Generator that will be used in the database 
     * layer.
     * 
     * @param \Psr\Log\LoggerInterface $log
     */
    public function setGenerator(SqlGeneratorInterface $gen)
    {
        $this->gen = $gen;
    }

    /**
     * Sets the name of the class that should be used to Execute queries.
     * 
     * @param string $class
     */
    public function setExecutorName($class)
    {
        $this->execClass = $class;
    }

    /**
     * Sets the name of the Database class that should be returned by the
     * factory.
     * 
     * @param string $class
     */
    public function setDatabaseName($class)
    {
        $this->dbClass = $class;
    }
}
