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

    public function __construct($dsn, $username, $password, array $options = array())
    {
        $this->dsn      = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options  = $options;
        $this->log      = new NullLogger();
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
     * Returns the configured database layer.
     * 
     * @return \Rawebone\Ormish\Database
     */
    public function build()
    {
        $pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
        $gen = new GenericSqlGenerator();
        $pop = new Populator();
        
        return new Database(new Executor($pdo, $this->log), $gen, $pop);
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
}
