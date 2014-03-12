<?php

namespace spec\Rawebone\Ormish\Utilities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

/**
 * @property int $test This is a test var to make sure the manager behaves correctly
 */
class MetaDataManagerSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Utilities\MetaDataManager');
    }
    
    function it_should_return_an_array_of_names_to_types()
    {
        $this->properties(__CLASS__)->shouldReturn(array("test" => "int"));
    }
}
