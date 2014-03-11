<?php

namespace spec\Rawebone\Ormish;

use Rawebone\Ormish\Actions\ActionFactory;
use Rawebone\Ormish\Actions\Create;
use Rawebone\Ormish\Actions\Deleter;
use Rawebone\Ormish\Actions\Find;
use Rawebone\Ormish\Actions\FindOneWhere;
use Rawebone\Ormish\Actions\FindWhere;
use Rawebone\Ormish\Actions\Saver;
use Rawebone\Ormish\Entity;
use Rawebone\Ormish\Table;
use Rawebone\Ormish\Database;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GatewaySpec extends ObjectBehavior
{
    function let(Database $db, Table $tbl, ActionFactory $factory)
    {
        $this->beConstructedWith($db, $tbl, $factory);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Gateway');
    }

    function it_should_create($factory, $db, $tbl, Create $create)
    {
        $gw = Argument::type('Rawebone\Ormish\Gateway');

        $factory->create('Rawebone\Ormish\Actions\Create', $db, $tbl, $gw)
                ->willReturn($create);

        $create->run(array())->willReturn(true);

        $this->create()->shouldReturn(true);
    }

    function it_should_delete($factory, $db, $tbl, Deleter $deleter, Entity $entity)
    {
        $gw = Argument::type('Rawebone\Ormish\Gateway');

        $factory->create('Rawebone\Ormish\Actions\Deleter', $db, $tbl, $gw)
            ->willReturn($deleter);

        $deleter->run($entity)->willReturn(true);

        $this->delete($entity)->shouldReturn(true);
    }

    function it_should_find($factory, $db, $tbl, Find $find)
    {
        $gw = Argument::type('Rawebone\Ormish\Gateway');

        $factory->create('Rawebone\Ormish\Actions\Find', $db, $tbl, $gw)
            ->willReturn($find);

        $find->run(1)->willReturn(true);

        $this->find(1)->shouldReturn(true);
    }

    function it_should_find_where($factory, $db, $tbl, FindWhere $find)
    {
        $gw = Argument::type('Rawebone\Ormish\Gateway');

        $factory->create('Rawebone\Ormish\Actions\FindWhere', $db, $tbl, $gw)
            ->willReturn($find);

        $find->run("a = ?", 1)->willReturn(true);

        $this->findWhere("a = ?", 1)->shouldReturn(true);
    }

    function it_should_findone_where($factory, $db, $tbl, FindOneWhere $find)
    {
        $gw = Argument::type('Rawebone\Ormish\Gateway');

        $factory->create('Rawebone\Ormish\Actions\FindOneWhere', $db, $tbl, $gw)
            ->willReturn($find);

        $find->run("a = ?", 1)->willReturn(true);

        $this->findOneWhere("a = ?", 1)->shouldReturn(true);
    }

    function it_should_save($factory, $db, $tbl, Saver $saver, Entity $entity)
    {
        $gw = Argument::type('Rawebone\Ormish\Gateway');

        $factory->create('Rawebone\Ormish\Actions\Saver', $db, $tbl, $gw)
            ->willReturn($saver);

        $saver->run($entity)->willReturn(true);

        $this->save($entity)->shouldReturn(true);
    }
}
