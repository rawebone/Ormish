<?php

namespace spec\Rawebone\Ormish;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class NullShadowSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\NullShadow');
    }
    
    function it_should_never_record_state()
    {
        $this->update(array("a" => "help", "b" => "no"))->shouldReturn(null);
        $this->changes(array("a" => "hello"))->shouldReturn(array());
    }
}
