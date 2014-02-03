<?php

namespace spec\Rawebone\Ormish;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ErrorSpec extends ObjectBehavior
{
    function let()
    {
        $code   = 1;
        $msg    = "Failed";
        $query  = "SELECT * FROM whatever";
        $params = array();
        
        $this->beConstructedWith($code, $msg, $query, $params);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Error');
    }
    
    function it_should_hold_state()
    {
        $this->code()->shouldReturn(1);
        $this->message()->shouldReturn("Failed");
        $this->query()->shouldReturn("SELECT * FROM whatever");
        $this->params()->shouldReturn(array());
    }
}
