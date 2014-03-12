<?php

namespace spec\Rawebone\Ormish\Utilities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ShadowSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Utilities\Shadow');
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

    function it_should_compute_changes_from_datetime_objects(\DateTime $dt)
    {
        $dt->getTimestamp()->willReturn(12345);
        $this->update(array("a" => $dt));

        $dt->getTimestamp()->willReturn(123456);
        $this->changes(array("a" => $dt))->shouldReturn(array("a" => $dt));
    }
}
