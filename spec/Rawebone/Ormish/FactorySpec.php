<?php

namespace spec\Rawebone\Ormish;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class FactorySpec extends ObjectBehavior
{
    function let()
    {
        $dsn = "sqlite::memory:";
        $username = "";
        $password = "";
        $options  = array();
        
        $this->beConstructedWith($dsn, $username, $password, $options);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Factory');
    }
    
    function it_should_build()
    {
        $this->build()->shouldReturnAnInstanceOf('Rawebone\Ormish\Database');
    }
}
