<?php

namespace spec\Rawebone\Ormish;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TableSpec extends ObjectBehavior
{
    function let()
    {
        $this->beConstructedWith("Model", "Table");
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Table');
    }
    
    function it_should_have_a_defaults()
    {
        $this->model()->shouldReturn("Model");
        $this->table()->shouldReturn("Table");
        
        $this->id()->shouldReturn("id");
        $this->softDelete()->shouldReturn(true);
        $this->readOnly()->shouldReturn(false);
    }
    
    function it_should_override_defaults()
    {
        $this->beConstructedWith("Model", "Table", "my_id", false, true);
        
        $this->id()->shouldReturn("my_id");
        $this->softDelete()->shouldReturn(false);
        $this->readOnly()->shouldReturn(true);
    }
}
