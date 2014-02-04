<?php

namespace spec\Rawebone\Ormish;

use Rawebone\Ormish\Executor;
use Rawebone\Ormish\Populator;
use Rawebone\Ormish\SqlGeneratorInterface;
use Rawebone\Ormish\Table;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DatabaseSpec extends ObjectBehavior
{
    function let(Executor $exec, Populator $pop, SqlGeneratorInterface $gen)
    {
        $this->beConstructedWith($exec, $gen, $pop);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Database');
    }
    
    function it_should_attach_and_return()
    {
        $this->attach(new Table("Blah", "blah"))->shouldReturn(null);
        $this->get("blah")->shouldReturnAnInstanceOf('Rawebone\Ormish\Gateway');
    }
    
    function it_should_throw_an_exception_when_getting_invalid_table()
    {
        $this->shouldThrow('Rawebone\Ormish\InvalidTableException')
             ->during("get", array("invalid"));
    }
}
