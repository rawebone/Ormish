<?php

namespace spec\Rawebone\Ormish;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ShadowSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Shadow');
    }
    
    function it_should_update()
    {
        $this->update(array("a" => "hello", "b" => "no"))->shouldReturn(null);
    }
    
    function it_should_compute_changes()
    {
        $this->update(array("a" => "hello", "b" => "no"));
        $this->changes(array("a" => "help"))->shouldReturn(array("a" => "help"));
    }
}
