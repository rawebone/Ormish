<?php

namespace Rawebone\Ormish;

/**
 * Error encapsulates a problem during the execution of queries on the database
 * so that they can be safely handled. This is better than using exceptions as
 * that can cause unspecified behaviour and adds additional complexity to the
 * Domain Layers.
 */
class Error
{
    protected $code;
    protected $msg;
    protected $query;
    protected $params;
    
    public function __construct($code, $msg, $query, array $params)
    {
        $this->code = $code;
        $this->msg = $msg;
        $this->query = $query;
        $this->params = $params;
    }

    public function code()
    {
        return $this->code;
    }

    public function message()
    {
        return $this->msg;
    }

    public function query()
    {
        return $this->query;
    }

    public function params()
    {
        return $this->params;
    }
}
