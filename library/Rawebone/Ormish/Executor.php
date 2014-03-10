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

        // Ensure that the connection is set to throw Exceptions as we will need this
        // when handling statement execution.
        $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Returns the encapsulated connection object.
     * 
     * @return \PDO
     */
    public function connection()
    {
        return $this->pdo;
    }
    
    /**
     * Marshalls call to PDO::beginTransaction for convenience.
     * 
     * @see \PDO::beginTransaction()
     * @return bool
     */
    public function beginTransaction()
    {
        return $this->pdo->beginTransaction();
    }
    
    /**
     * Marshalls call to PDO::commit for convenience.
     * 
     * @see \PDO::commit()
     * @return bool
     */
    public function commit()
    {
        return $this->pdo->commit();
    }
    
    /**
     * Marshalls call to PDO::rollBack for convenience.
     * 
     * @see \PDO::rollBack()
     * @return bool
     */
    public function rollback()
    {
        return $this->pdo->rollBack();
    }
    
    /**
     * Executes a query and returns a Statement object or an Error.
     * 
     * @param string $query
     * @param array $params
     * @return \PDOStatement|\Rawebone\Ormish\Error
     */
    public function query($query, array $params)
    {
        return $this->handle($query, $params);
    }
    
    /**
     * Executes a statement and returns whether it was successful.
     * 
     * @param string $query
     * @param array $params
     * @return true|\Rawebone\Ormish\Error
     */
    public function exec($query, array $params)
    {
        return (($err = $this->handle($query, $params)) instanceof Error ? $err : true);
    }
    
    /**
     * Marshalls call to PDO::lastInsertId for convenience.
     * 
     * @see \PDO::lastInsertId()
     * @return string
     */
    public function lastInsertId()
    {
        return $this->pdo->lastInsertId();
    }
    
    /**
     * This does the leg work for querying and execution.
     * 
     * @param string $query
     * @param array $params
     * @return \PDOStatement|\Rawebone\Ormish\Error
     */
    protected function handle($query, array $params)
    {
        try {
            $stmt = $this->pdo->prepare($query);
            $stmt->execute($params);
            $this->log->info("Successful Query: $query [Params: {$this->buildParamString($params)}]");
            return $stmt;
        } catch (\PDOException $ex) {
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
