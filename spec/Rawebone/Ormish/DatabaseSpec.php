<?php

namespace spec\Rawebone\Ormish;

use Rawebone\Ormish\Actions\ActionFactory;
use Rawebone\Ormish\Executor;
use Rawebone\Ormish\Table;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;
use Rawebone\Ormish\Utilities\EntityManager;

class DatabaseSpec extends ObjectBehavior
{
    function let(Executor $exec, ActionFactory $factory, EntityManager $entityManager)
    {
        $this->beConstructedWith($exec, $factory, $entityManager);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Database');
        $this->getExecutor()->shouldBeAnInstanceOf('Rawebone\Ormish\Executor');
    }

    /**
     * @param \Rawebone\Ormish\Utilities\EntityManager $entityManager
     */
    function it_should_attach_and_return($entityManager, Table $table)
    {
        $entityManager->table('Blah')
                      ->willReturn($table)
                      ->shouldBeCalled();

        $table->model()->willReturn("Blah");
        $this->attach('Blah')->shouldReturn(true);

        // Standard getting
        $this->get("Blah")->shouldReturnAnInstanceOf('Rawebone\Ormish\Gateway');
    }
    
    function it_should_throw_an_exception_when_getting_invalid_entity()
    {
        $this->shouldThrow('Rawebone\Ormish\Exceptions\InvalidTableException')
             ->during("get", array("invalid"));
    }
}
