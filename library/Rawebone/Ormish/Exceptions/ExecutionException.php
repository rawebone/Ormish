<?php

namespace Rawebone\Ormish\Exceptions;

class ExecutionException extends \RuntimeException
{
    protected $sqlStateCode;
    protected $errorMsg;
    protected $query;
    protected $params;

    public function __construct($code, $msg, $query, array $params)
    {
        $this->sqlStateCode = $code;
        $this->errorMsg = $msg;
        $this->query = $query;
        $this->params = $params;

        $exMsg = "Execution Exception: [$code] $msg";
        parent::__construct($exMsg);
    }

    public function getSqlState()
    {
        return $this->sqlStateCode;
    }

    public function getErrorMsg()
    {
        return $this->errorMsg;
    }

    public function getQueryString()
    {
        return $this->query;
    }

    public function getQueryParams()
    {
        return $this->params;
    }
}
