<?php

namespace spec\Rawebone\Ormish\Actions;

use Rawebone\Ormish\Executor;
use Rawebone\Ormish\SqlGeneratorInterface;
use Rawebone\Ormish\Utilities\Caster;
use Rawebone\Ormish\Utilities\Populater;
use Rawebone\Ormish\Utilities\ObjectCreator;
use Rawebone\Ormish\Utilities\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class ActionFactorySpec extends ObjectBehavior
{
    function let(EntityManager $em, Executor $ex, Populater $pop,
        SqlGeneratorInterface $gen, ObjectCreator $oc, Caster $caster)
    {
        $this->beConstructedWith($em, $ex, $pop, $gen, $oc, $caster);
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
     * @param \Rawebone\Ormish\Utilities\Caster $caster
     */
    function it_should_return_an_action($em, $ex, $pop, $gen, $oc, $gw, $db, $tbl, $caster)
    {
        $oc->create('Rawebone\Ormish\Actions\Saver', array(
                $db,
                $gw,
                $em,
                $tbl,
                $ex,
                $pop,
                $gen,
                $caster
            ))->willReturn(true);

        $this->create('Rawebone\Ormish\Actions\Saver', $db, $tbl, $gw)
             ->shouldReturn(true);
    }
}
