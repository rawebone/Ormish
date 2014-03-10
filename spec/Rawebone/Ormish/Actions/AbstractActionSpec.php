<?php

namespace spec\Rawebone\Ormish\Actions;

use Rawebone\Ormish\Utilities\EntityManager;
use Rawebone\Ormish\GatewayInterface;
use Rawebone\Ormish\Database;
use Rawebone\Ormish\Executor;
use Rawebone\Ormish\Populater;
use Rawebone\Ormish\Table;
use Rawebone\Ormish\SqlGeneratorInterface;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class AbstractActionSpec extends ObjectBehavior
{
    function let(EntityManager $em, GatewayInterface $gw, Database $db, 
        Table $tbl, Executor $ex, Populater $pop, SqlGeneratorInterface $gen)
    {
        $this->beConstructedWith($db, $gw, $em, $tbl, $ex, $pop, $gen);
    }
}
