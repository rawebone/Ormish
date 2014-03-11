<?php

namespace spec\Rawebone\Ormish;

use Rawebone\Ormish\Utilities\EntityManager;
use Rawebone\Ormish\Executor;
use Rawebone\Ormish\Populater;
use Rawebone\Ormish\SqlGeneratorInterface;
use Rawebone\Ormish\Table;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class DatabaseSpec extends ObjectBehavior
{
    function let(Executor $exec, Populater $pop, SqlGeneratorInterface $gen,
        EntityManager $em)
    {
        $this->beConstructedWith($exec, $gen, $pop, $em);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Database');
        $this->getExecutor()->shouldBeAnInstanceOf('Rawebone\Ormish\Executor');
    }
    
    function it_should_attach_and_return()
    {
        // Fluent attaching
        $this->attach(new Table("Blah", "blah"))
             ->shouldReturnAnInstanceOf('Rawebone\Ormish\Database');
        
        // Standard getting
        $this->get("blah")->shouldReturnAnInstanceOf('Rawebone\Ormish\Gateway');
        
        // "Magic" getting, nicer for more natural usage.
        $this->blah()->shouldReturnAnInstanceOf('Rawebone\Ormish\Gateway');
    }
    
    function it_should_throw_an_exception_when_getting_invalid_table()
    {
        $this->shouldThrow('Rawebone\Ormish\Exceptions\InvalidTableException')
             ->during("get", array("invalid"));
    }
}
