<?php

namespace Rawebone\Ormish\Relationships;

use Rawebone\Ormish\Database;

abstract class AbstractRelationship
{
    /**
     * @var \Rawebone\Ormish\Database
     */
    protected $database;

    public function __construct(Database $database)
    {
        $this->database = $database;
    }
}
