<?php

namespace spec\Rawebone\Ormish\Utilities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ObjectCreatorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Utilities\ObjectCreator');
    }
    
    function it_should_return_an_instance_by_name()
    {
        $this->create('Rawebone\Ormish\Utilities\NullShadow', array())
             ->shouldReturnAnInstanceOf('Rawebone\Ormish\Utilities\NullShadow');
    }
    
    function it_should_throw_a_reflection_exception_if_invalid_name_passed()
    {
        $this->shouldThrow('ReflectionException')->during("create", array(
            'Non\Existant\ClassName'
        ));
    }
}
