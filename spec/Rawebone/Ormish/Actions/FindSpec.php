<?php

namespace spec\Rawebone\Ormish\Actions;

use Rawebone\Ormish\Exceptions\ExecutionException;
use Prophecy\Argument;

class FindSpec extends AbstractActionSpec
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Actions\Find');
    }

    /**
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Executor $ex
     * @param \Rawebone\Ormish\Utilities\Populater $pop
     * @param \Rawebone\Ormish\Utilities\EntityManager $em
     * @param \Rawebone\Ormish\Database $db
     * @param \Rawebone\Ormish\GatewayInterface $gw
     */
    function it_should_find_a_record($ent, $tbl, $gen, $ex, $pop, $em, $db, $gw, \PDOStatement $stmt)
    {
        $tbl->table()->willReturn("table");
        $tbl->id()->willReturn("id");
        $tbl->model()->willReturn("Entity");
        $tbl->readOnly()->willReturn(true);

        $gen->find("table", "id")->willReturn("query");
        $ex->query("query", array(1))->willReturn($stmt);

        $pop->populate($stmt, "Entity")->willReturn(array($ent));
        $em->prepare($ent, $gw, $db, true)->shouldBeCalled();

        $this->run(1)->shouldReturn($ent);
    }

    /**
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Executor $ex
     */
    function it_should_return_null_if_a_execution_fails($tbl, $gen, $ex)
    {
        $tbl->table()->willReturn("table");
        $tbl->id()->willReturn("id");
        $tbl->model()->willReturn("Entity");
        $tbl->readOnly()->willReturn(true);

        $gen->find("table", "id")->willReturn("query");
        $ex->query("query", array(1))->willThrow(new ExecutionException("", "", "", array()));

        $this->run(1)->shouldReturn(null);
    }

    /**
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Executor $ex
     * @param \Rawebone\Ormish\Utilities\Populater $pop
     */
    function it_should_return_null_if_no_records_returned($tbl, $gen, $ex, $pop, \PDOStatement $stmt)
    {
        $tbl->table()->willReturn("table");
        $tbl->id()->willReturn("id");
        $tbl->model()->willReturn("Entity");
        $tbl->readOnly()->willReturn(true);

        $gen->find("table", "id")->willReturn("query");
        $ex->query("query", array(1))->willReturn($stmt);

        $pop->populate($stmt, "Entity")->willReturn(array());

        $this->run(1)->shouldReturn(null);
    }
}
