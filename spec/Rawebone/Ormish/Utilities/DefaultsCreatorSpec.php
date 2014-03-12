<?php

namespace spec\Rawebone\Ormish\Utilities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DefaultsCreatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Utilities\DefaultsCreator');
    }
    
    function it_should_return_defaults()
    {
        $types = array(
            "string" => "string",
            "int" => "int",
            "boolean" => "bool",
            "null" => "null"
        );
        
        $expected = array(
            "string" => "",
            "int" => 0,
            "boolean" => false,
            "null" => null
        );
        
        $this->make($types)->shouldReturn($expected);
    }

    function it_should_return_default_datetime_object()
    {
        $defaults = $this->make(array("dt" => "DateTime"));
        $defaults["dt"]->shouldBeAnInstanceOf('DateTime');
    }
}
