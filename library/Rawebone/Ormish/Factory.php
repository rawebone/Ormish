<?php

namespace Rawebone\Ormish;

use Psr\Log\NullLogger;
use Psr\Log\LoggerInterface;

class Factory
{
    protected $dsn;
    protected $username;
    protected $password;
    protected $options;
    protected $log;
    protected $gen;

    public function __construct($dsn, $username, $password, array $options = array())
    {
        $this->dsn      = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options  = $options;
        $this->log      = new NullLogger();
        $this->gen      = new GenericSqlGenerator();
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
     * Returns the configured database layer.
     * 
     * @return \Rawebone\Ormish\Database
     */
    public function build()
    {
        $pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
        $pop = new Populator();
        
        return new Database(new Executor($pdo, $this->log), $this->gen, $pop);
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
}
