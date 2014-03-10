<?php

namespace spec\Rawebone\Ormish;

use Rawebone\Ormish\Executor;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class TransactionSpec extends ObjectBehavior
{
    function it_should_succeed(Executor $exec)
    {
        $exec->beginTransaction()->shouldBeCalled();
        $exec->commit()->shouldBeCalled();

        $this->beConstructedWith($exec, function () {

        });

        $this->run();
    }

    function it_should_fail(Executor $executor)
    {
        $executor->beginTransaction()->shouldBeCalled();
        $executor->rollback()->shouldBeCalled();

        $this->beConstructedWith($executor, function () {
            throw new \Exception();
        });

        $this->shouldThrow('Exception')->during('run');
    }
}
