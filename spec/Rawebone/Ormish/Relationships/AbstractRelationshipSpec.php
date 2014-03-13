<?php

namespace spec\Rawebone\Ormish\Relationships;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rawebone\Ormish\Database;

abstract class AbstractRelationshipSpec extends ObjectBehavior
{
    function let(Database $database)
    {
        $this->beConstructedWith($database);
    }
}
