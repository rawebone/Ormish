<?php

namespace spec\Rawebone\Ormish\Actions;

use Rawebone\Ormish\GatewayInterface;
use Rawebone\Ormish\Database;
use Rawebone\Ormish\Table;
use Rawebone\Ormish\Executor;
use Rawebone\Ormish\SqlGeneratorInterface;
use Rawebone\Ormish\Utilities\Populater;
use Rawebone\Ormish\Utilities\ObjectCreator;
use Rawebone\Ormish\Utilities\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionFactorySpec extends ObjectBehavior
{
    function let(EntityManager $em, Executor $ex, Populater $pop,
        SqlGeneratorInterface $gen, ObjectCreator $oc)
    {
        $this->beConstructedWith($em, $ex, $pop, $gen, $oc);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Actions\ActionFactory');
    }

    /**
     * @param \Rawebone\Ormish\Utilities\ObjectCreator $oc
     * @param \Rawebone\Ormish\GatewayInterface $gw
     * @param \Rawebone\Ormish\Database $db
     * @param \Rawebone\Ormish\Table $tbl
     */
    function it_should_return_an_action($em, $ex, $pop, $gen, $oc, $gw, $db, $tbl)
    {
        $oc->create('Rawebone\Ormish\Actions\Saver', array(
                $db,
                $gw,
                $em,
                $tbl,
                $ex,
                $pop,
                $gen
            ))->willReturn(true);

        $this->create('Rawebone\Ormish\Actions\Saver', $db, $tbl, $gw)
             ->shouldReturn(true);
    }
}
