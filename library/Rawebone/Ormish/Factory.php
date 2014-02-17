<?php

namespace Rawebone\Ormish;

use Psr\Log\NullLogger;

class Factory
{
    protected $dsn;
    protected $username;
    protected $password;
    protected $options;

    public function __construct($dsn, $username, $password, array $options = array())
    {
        $this->dsn      = $dsn;
        $this->username = $username;
        $this->password = $password;
        $this->options  = $options;
    }

    public function build()
    {
        $pdo = new \PDO($this->dsn, $this->username, $this->password, $this->options);
        $gen = new GenericSqlGenerator();
        $pop = new Populator();
        $log = new NullLogger();
        
        return new Database(new Executor($pdo, $log), $gen, $pop);
    }
}
