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
        $cls = "My\\Cls";
        
        $stmt->setFetchMode(\PDO::FETCH_CLASS, $cls)->shouldBeCalled();
        $stmt->fetchObject()->willReturn(null);
        
        $this->populate($stmt, $cls)->shouldReturn(array());
    }
}
