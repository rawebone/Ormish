<?php

namespace spec\Rawebone\Ormish;

use Rawebone\Ormish\Executor;
use Rawebone\Ormish\Populator;
use Rawebone\Ormish\SqlGeneratorInterface;
use Rawebone\Ormish\Table;
use Rawebone\Ormish\Database;
use Rawebone\Ormish\Entity;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GatewaySpec extends ObjectBehavior
{
    function let(Database $db, Table $tbl, Executor $exec, Populator $pop, 
        SqlGeneratorInterface $gen, Entity $ent)
    {
        $this->beConstructedWith($db, $tbl, $gen, $exec, $pop);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Gateway');
    }
    
    function it_should_create($tbl)
    {
        $tbl->model()->willReturn('Rawebone\Ormish\Entity');
        $tbl->id()->willReturn("id");
        $tbl->readOnly()->willReturn(false);
        $this->create()->shouldReturnAnInstanceOf('Rawebone\Ormish\Entity');
    }
    
    function it_should_not_attempt_save_or_delete_if_read_only($tbl, $ent)
    {
        $tbl->readOnly()->willReturn(true);
        
        $this->save($ent)->shouldReturn(false);
        $this->delete($ent)->shouldReturn(false);
    }
    
    /**
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Executor $exec
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     */
    function it_should_insert($tbl, $ent, $exec, $gen)
    {
        $tbl->readOnly()->willReturn(false);
        $tbl->table()->willReturn('whatever');
        $tbl->id()->willReturn('id');

        $ent->id = null;
        $ent->all()->willReturn(array());
        
        $gen->insert('whatever', array())->willReturn(array('whatever', array()));
        $exec->exec('whatever', array())->willReturn(false);
        
        $this->save($ent)->shouldReturn(false);
        
        $exec->exec('whatever', array())->willReturn(true);
        $exec->lastInsertId()->willReturn("1");
        $this->save($ent)->shouldReturn(true);
        
        if ($ent->id !== 1) {
            throw new \Exception("ID not set");
        }
    }
    
    /**
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Executor $exec
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     */
    function it_should_update($tbl, $ent, $exec, $gen)
    {
        $tbl->readOnly()->willReturn(false);
        $tbl->table()->willReturn('whatever');
        $tbl->id()->willReturn('id');

        $ent->id = 1;
        $ent->all()->willReturn(array());
        
        $gen->update('whatever', array(), 'id', 1)->willReturn(array('whatever', array()));
        $exec->exec('whatever', array())->willReturn(false);
        
        $this->save($ent)->shouldReturn(false);
        
        $exec->exec('whatever', array())->willReturn(true);
        $this->save($ent)->shouldReturn(true);
    }
    
    /**
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Executor $exec
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     */
    function it_should_soft_delete($tbl, $ent, $exec, $gen)
    {
        $tbl->id()->willReturn("id");
        $tbl->table()->willReturn("tbl");
        $tbl->softDelete()->willReturn(true);
        $tbl->readOnly()->willReturn(false);
        
        $ent->id = 1;
        
        $gen->delete("tbl", "id", true)->willReturn("DELETE");
        $exec->exec("DELETE", array(1))->willReturn(true);
        
        $this->delete($ent)->shouldReturn(true);
    }
    
    /**
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Executor $exec
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Populator $pop
     */
    function it_should_find($tbl, $exec, $gen, $pop, \PDOStatement $stmt, $ent)
    {
        $tbl->model()->willReturn('Rawebone\Ormish\Entity');
        $tbl->readOnly()->willReturn(true);
        $tbl->table()->willReturn('whatever');
        $tbl->id()->willReturn('id');
        
        $gen->find('whatever', 'id')->willReturn('whatever');
        $exec->query('whatever', array(1))->willReturn($stmt);
        
        // @todo This should account for Rawebone\Ormish\Error being returned
        //  by the Exector
        
        $pop->populate($stmt, 'Rawebone\Ormish\Entity')->willReturn(array());
        $this->find(1)->shouldReturn(null);
        
        $ent->all()->willReturn(array());
        $ent->letDatabase(Argument::any())->shouldBeCalled();
        $ent->letShadow(Argument::any())->shouldBeCalled();
        $ent->letGateway(Argument::any())->shouldBeCalled();
        
        $pop->populate($stmt, 'Rawebone\Ormish\Entity')->willReturn(array($ent));
        $this->find(1)->shouldReturn($ent);
    }
    
    /**
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Executor $exec
     * @param \Rawebone\Ormish\SqlGeneratorInterface $gen
     * @param \Rawebone\Ormish\Populator $pop
     */
    function it_should_find_with_conditions($tbl, $exec, $gen, $pop, \PDOStatement $stmt)
    {
        
    }
}
