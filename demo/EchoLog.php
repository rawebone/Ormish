<?php

class EchoLog extends \Psr\Log\AbstractLogger
{
    /**
     * Logs with an arbitrary level.
     *
     * @param mixed $level
     * @param string $message
     * @param array $context
     * @return null
     */
    public function log($level, $message, array $context = array())
    {
        echo microtime(true), " - ($level) $message", PHP_EOL;
    }
}