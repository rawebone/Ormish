<?php

namespace Rawebone\Ormish;

use PDO;
use Psr\Log\LoggerInterface;

/**
 * Executor is a thin wrapper over PDO to provide Error handling, Logging and
 * execution of queries via a simple mechanism.
 */
class Executor
{
    protected $pdo;
    protected $log;

    public function __construct(PDO $pdo, LoggerInterface $log)
    {
        $this->pdo = $pdo;
        $this->log = $log;
    }

    public function query($query, array $params)
    {
        return $this->handle($query, $params);
    }
    
    public function exec($query, array $params)
    {
        return (($err = $this->handle($query, $params)) instanceof Error ? $err : true);
    }
    
    protected function handle($query, array $params)
    {
        $stmt = $this->pdo->prepare($query);
        
        if ($stmt->execute($params)) {
            $this->log->info("Successful Query: $query [Params: {$this->buildParamString($params)}]");
        } else {
            $error = $this->buildError($query, $params);
            $this->logError($error);
            return $error;
        }
        
        return $stmt;
    }
    
    /**
     * Returns a standardised error for the connection.
     * 
     * @param string $query
     * @param array $params
     * @return \Rawebone\Ormish\Error
     */
    protected function buildError($query, array $params)
    {
        $info = $this->pdo->errorInfo();
        return new Error($info[0], "{$info[2]} ($info[1])", $query, $params);
    }
    
    /**
     * Converts an Error to a log entry for debugging and maintenance.
     * 
     * @param \Rawebone\Ormish\Error $error
     */
    protected function logError(Error $error)
    {
        $msg = sprintf(
                "Failed Query: %s [Params: %s]; Error: %s %s", 
                $error->query(), 
                $this->buildParamString($error->params()),
                $error->code(), 
                $error->message()
        );
        
        $this->log->error($msg);
    }
    
    /**
     * Returns a joined representation of the parameters passed with a query.
     * 
     * @param array $params
     * @return string
     */
    protected function buildParamString(array $params)
    {
        $joined = array();
        foreach ($params as $key => $value) {
            $joined[] = "$key = $value";
        }
        return join(", ", $joined);
    }
}
