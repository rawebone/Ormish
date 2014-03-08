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
    protected $pop;

    public function __construct($dsn, $username, $password, array $options = array())
    {
        $this->dsn      = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options  = $options;
        
        // Default objects.
        $this->log = new NullLogger();
        $this->gen = new GenericSqlGenerator();
        $this->pop = new Populator();
    }

    /**
     * Returns the configured database layer.
     * 
     * @return \Rawebone\Ormish\Database
     */
    public function build()
    {
        $pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
        
        return new Database(new Executor($pdo, $this->log), $this->gen, $this->pop);
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
     * Returns the instance of the Populator that will be used in the database 
     * layer.
     * 
     * @return \Rawebone\Ormish\Populator
     */
    public function populator()
    {
        return $this->pop;
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
     * Sets the instance of the Populator that will be used in the database 
     * layer.
     * 
     * @param \Rawebone\Ormish\Populator $pop
     */
    public function setPopulator(Populator $pop)
    {
        $this->pop = $pop;
    }
}
