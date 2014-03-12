<?php

namespace Rawebone\Ormish\Utilities;

/**
 * Shadows are used to record and compute changes in state in Entities.
 */
class Shadow
{
    protected $state = array();

    /**
     * Sets the state that should be tracked so that changes can be computed.
     * 
     * @param array $data
     */
    public function update(array $data)
    {
        foreach ($data as $key => $value) {
            $this->state[$key] = $this->hash($value);
        }
    }

    /**
     * Computes the difference between the passed data (representing our 
     * current state) against the tracked state and returns a key-value array
     * with the differences.
     * 
     * @param array $data
     * @return array
     */
    public function changes(array $data)
    {
        $changes = array();
        
        foreach ($this->state as $key => $value) {
            if (isset($data[$key]) && $this->hash($data[$key]) !== $value) {
                $changes[$key] = $data[$key];
            }
        }
        
        return $changes;
    }
    
    /**
     * Returns a hash representation of the value being tracked.
     * 
     * @param mixed $value
     * @return string
     */
    protected function hash($value)
    {
        if ($value instanceof \DateTime) {
            return md5((string)$value->getTimestamp());
        }

        return md5((string)$value);
    }
}
