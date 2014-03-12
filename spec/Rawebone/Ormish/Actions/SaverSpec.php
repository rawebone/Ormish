<?php

namespace spec\Rawebone\Ormish\Actions;

use Rawebone\Ormish\Exceptions\ExecutionException;
use Prophecy\Argument;

class SaverSpec extends AbstractActionSpec
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Actions\Saver');
    }

    /**
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Table $tbl
     */
    function it_should_not_save_if_table_is_readonly($ent, $tbl)
    {
        $tbl->readOnly()->willReturn(true);
        $this->run($ent)->shouldReturn(false);
    }

    /**
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Executor $ex
     * @param \Rawebone\Ormish\Utilities\Caster $caster
     * @param \Rawebone\Ormish\Utilities\EntityManager $em
     */
    function it_should_try_to_insert($ent, $tbl, $gen, $ex, $caster, $em)
    {
        $tbl->readOnly()->willReturn(false);
        $tbl->id()->willReturn("id");
        $tbl->table()->willReturn("table");
        $tbl->model()->willReturn("Entity");

        $ent->id = null;
        $ent->all()->willReturn(array());

        $em->properties('Entity')->willReturn(array());
        $caster->toDbTypes(array(), array())->willReturn(array());

        $gen->insert("table", array())->willReturn(array("query", array()));

        $ex->exec("query", array())->willReturn(true);
        $ex->lastInsertId()->willReturn("1");

        $this->run($ent)->shouldReturn(true);

        if ($ent->id !== 1) {
            throw new \Exception();
        }
    }

    /**
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Executor $ex
     * @param \Rawebone\Ormish\Utilities\Caster $caster
     * @param \Rawebone\Ormish\Utilities\EntityManager $em
     */
    function it_should_try_to_insert_and_fail($ent, $tbl, $gen, $ex, $caster, $em)
    {
        $tbl->readOnly()->willReturn(false);
        $tbl->id()->willReturn("id");
        $tbl->table()->willReturn("table");
        $tbl->model()->willReturn("Entity");

        $ent->id = null;
        $ent->all()->willReturn(array());

        $em->properties('Entity')->willReturn(array());
        $caster->toDbTypes(array(), array())->willReturn(array());

        $gen->insert("table", array())->willReturn(array("query", array()));

        $ex->exec("query", array())->willThrow(new ExecutionException("", "", "", array()));

        $this->run($ent)->shouldReturn(false);
    }

    /**
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Executor $ex
     * @param \Rawebone\Ormish\Utilities\Caster $caster
     * @param \Rawebone\Ormish\Utilities\EntityManager $em
     */
    function it_should_try_to_update($ent, $tbl, $gen, $ex, $caster, $em)
    {
        $tbl->readOnly()->willReturn(false);
        $tbl->id()->willReturn("id");
        $tbl->table()->willReturn("table");
        $tbl->model()->willReturn("Entity");

        $ent->id = 1;
        $ent->changes()->willReturn(array());

        $em->properties('Entity')->willReturn(array());
        $caster->toDbTypes(array(), array())->willReturn(array());

        $gen->update("table", array(), "id", 1)->willReturn(array("query", array()));

        $ex->exec("query", array())->willReturn(true);

        $this->run($ent)->shouldReturn(true);
    }


    /**
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Executor $ex
     * @param \Rawebone\Ormish\Utilities\Caster $caster
     * @param \Rawebone\Ormish\Utilities\EntityManager $em
     */
    function it_should_try_to_update_and_fail($ent, $tbl, $gen, $ex, $caster, $em)
    {
        $tbl->readOnly()->willReturn(false);
        $tbl->id()->willReturn("id");
        $tbl->table()->willReturn("table");
        $tbl->model()->willReturn("Entity");

        $ent->id = 1;
        $ent->changes()->willReturn(array());

        $em->properties('Entity')->willReturn(array());
        $caster->toDbTypes(array(), array())->willReturn(array());

        $gen->update("table", array(), "id", 1)->willReturn(array("query", array()));

        $ex->exec("query", array())->willThrow(new ExecutionException("", "", "", array()));

        $this->run($ent)->shouldReturn(false);
    }
}
