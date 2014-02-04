<?php

namespace spec\Rawebone\Ormish;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PopulatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Populator');
    }
    
    function it_should_populate(\PDOStatement $stmt)
    {
        $cls = 'Rawebone\Ormish\Entity';
        $stmt->fetch(\PDO::FETCH_ASSOC)->shouldBeCalled();
        $this->populate($stmt, $cls)->shouldReturn(array());
    }
}
