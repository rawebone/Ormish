<?php

namespace spec\Rawebone\Ormish\Exceptions;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ExecutionExceptionSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Exceptions\ExecutionException');
    }

    function it_should_encapsulate_error_state()
    {
        $code   = "0";
        $msg    = "ABC (12)";
        $query  = "SELECT";
        $params = array();

        $this->beConstructedWith($code, $msg, $query, $params);

        $this->getSqlState()->shouldReturn($code);
        $this->getErrorMsg()->shouldReturn($msg);
        $this->getQueryString()->shouldReturn($query);
        $this->getQueryParams()->shouldReturn($params);
    }
}
