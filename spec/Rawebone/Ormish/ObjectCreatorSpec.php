<?php

namespace spec\Rawebone\Ormish;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectCreatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\ObjectCreator');
    }
    
    function it_should_return_an_instance_by_name()
    {
        $this->create('Rawebone\Ormish\NullShadow', array())
             ->shouldReturnAnInstanceOf('Rawebone\Ormish\NullShadow');
    }
    
    function it_should_throw_a_reflection_exception_if_invalid_name_passed()
    {
        $this->shouldThrow('ReflectionException')->during("create", array(
            'Non\Existant\ClassName'
        ));
    }
}
