<?php

namespace spec\Rawebone\Ormish\Actions;

use Rawebone\Ormish\Entity;
use Rawebone\Ormish\Exceptions\ExecutionException;
use Prophecy\Argument;

class DeleterSpec extends AbstractActionSpec
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Actions\Deleter');
    }
    
    function it_should_fail_if_table_is_readonly(Entity $ent, $tbl)
    {
        $tbl->readOnly()->willReturn(true);
        $this->run($ent)->shouldReturn(false);
    }
    
    function it_should_delete_a_record(Entity $ent, $tbl, $gen, $ex)
    {
        $ent->id = 1;
        
        $tbl->readOnly()->willReturn(false);
        $tbl->id()->willReturn("id");
        $tbl->table()->willReturn("tbl");
        $tbl->softDelete()->willReturn(true);
        
        $gen->delete("tbl", "id", true)->willReturn("query");
        $ex->exec("query", array(1))->willReturn(true);
        
        $this->run($ent)->shouldReturn(true);
    }

    function it_should_fail_to_delete_a_record(Entity $ent, $tbl, $gen, $ex)
    {
        $ent->id = 1;

        $tbl->readOnly()->willReturn(false);
        $tbl->id()->willReturn("id");
        $tbl->table()->willReturn("tbl");
        $tbl->softDelete()->willReturn(true);

        $gen->delete("tbl", "id", true)->willReturn("query");
        $ex->exec("query", array(1))->willThrow(new ExecutionException("", "", "", array()));

        $this->run($ent)->shouldReturn(false);
    }
}
