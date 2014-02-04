<?php

namespace spec\Rawebone\Ormish;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class GenericSqlGeneratorSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\GenericSqlGenerator');
    }
    
    function it_should_convert_a_date(\DateTime $dt)
    {
        $dt->format("Y-m-d")->shouldBeCalled();
        $this->convertDate($dt, false);
        
        $dt->format("Y-m-d H:i:s")->shouldBeCalled();
        $this->convertDate($dt, true);
    }
    
    function it_should_return_a_pk_select_query()
    {
        $this->find("whatever", "id")->shouldReturn("SELECT * FROM whatever WHERE deleted = 0 AND id = ?");
    }
    
    function it_should_return_a_conditional_select_query()
    {
        $this->findWhere("whatever", "this = ?")->shouldReturn("SELECT * FROM whatever WHERE deleted = 0 AND this = ?");
    }
    
    function it_should_return_an_insert_query_and_params()
    {
        $query = "INSERT INTO whatever (a, b, c) VALUES(?, ?, ?)";
        $params = array("a" => 1, "b" => 2, "c" => 3);
        
        $parts = $this->insert("whatever", $params);
        $parts->shouldBeArray();
        $parts[0]->shouldBe($query);
        $parts[1]->shouldBe(array_values($params));
    }
    
    function it_should_return_an_update_query_and_params()
    {
        $query = "UPDATE whatever SET a = ?, b = ?, c = ? WHERE id = ?";
        $params = array("a" => 1, "b" => 2, "c" => 3, "id" => 1);
        
        $parts = $this->update("whatever", $params, "id", 1);
        $parts->shouldBeArray();
        $parts[0]->shouldBe($query);
        $parts[1]->shouldBe(array_values($params));
    }
    
    function it_should_return_a_restore_query()
    {
        $this->restore("whatever", "id")->shouldReturn("UPDATE whatever SET deleted = 0 WHERE id = ?");
    }
    
    function it_should_return_a_soft_delete_query()
    {
        $this->delete("whatever", "id", true)->shouldReturn("UPDATE whatever SET deleted = 1 WHERE id = ?");
    }
    
    function it_should_return_a_hard_delete_query()
    {
        $this->delete("whatever", "id", false)->shouldReturn("DELETE FROM whatever WHERE id = ?");
    }
}
