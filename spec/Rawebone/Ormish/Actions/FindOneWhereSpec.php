<?php

namespace spec\Rawebone\Ormish\Actions;

use Rawebone\Ormish\Exceptions\ExecutionException;
use Prophecy\Argument;

class FindOneWhereSpec extends AbstractActionSpec
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Actions\FindOneWhere');
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
    function it_should_find_records($ent, $tbl, $gen, $ex, $pop, $em, $db, $gw, \PDOStatement $stmt)
    {
        $tbl->table()->willReturn("table");
        $tbl->readOnly()->willReturn(true);
        $tbl->model()->willReturn("Entity");
        $gen->findWhere("table", "a = 1")->willReturn("query");

        $ex->query("query", array())->willReturn($stmt);
        $pop->populate($stmt, "Entity")->willReturn(array($ent));

        $em->prepare($ent, $gw, $db, true)->shouldBeCalled();

        $this->run("a = 1")->shouldReturn($ent);
    }

    /**
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Executor $ex
     */
    function it_should_return_null_when_execution_fails($tbl, $gen, $ex)
    {
        $tbl->table()->willReturn("table");
        $gen->findWhere("table", "a = 1")->willReturn("query");

        $ex->query("query", array())->willThrow(new ExecutionException("", "", "", array()));

        $this->run("a = 1")->shouldReturn(null);
    }

    /**
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Executor $ex
     */
    function it_should_include_additional_params($tbl, $gen, $ex)
    {
        $tbl->table()->willReturn("table");
        $gen->findWhere("table", "a = ?")->willReturn("query");

        $ex->query("query", array(1))->willThrow(new ExecutionException("", "", "", array()));

        $this->run("a = ?", 1)->shouldReturn(null);
    }
}
