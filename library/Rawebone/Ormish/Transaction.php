<?php

namespace Rawebone\Ormish;

/**
 * Transaction provides a convenient way of operating in a Unit of Work. It
 * allows you to perform your database operations inside of a callback and
 * wraps this in a transaction, and will rollback on an exception.
 *
 * Should an exception be encountered, it will be re-thrown after correct
 * handling.
 */
class Transaction
{
    protected $executor;
    protected $callback;

    public function __construct(Executor $executor, $callback)
    {
        $this->executor = $executor;
        $this->callback = $callback;
    }

    public function run()
    {
        try {
            $this->executor->beginTransaction();

            $cb = $this->callback;
            $cb();

            $this->executor->commit();
        } catch (\Exception $ex) {
            $this->executor->rollback();
            throw $ex; // Allow higher level processing of the error
        }
    }
}
