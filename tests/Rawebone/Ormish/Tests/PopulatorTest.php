<?php
namespace Rawebone\Ormish\Tests;

use Rawebone\Ormish\Populator;

class PopulatorTest extends TestCase
{
    public function testPopulates()
    {
        $stmt = $this->prophet->prophesize("PDOStatement");
        $stmt->setFetchMode(\PDO::FETCH_CLASS, "\\stdClass")->shouldBeCalled();
        $stmt->fetchObject()->willReturn(null)->shouldBeCalled();
        
        $pop = new Populator();
        $res = $pop->populate($stmt->reveal(), "\\stdClass");
        
        $this->assertInternalType("array", $res);
        $this->assertCount(0, $res);
    }
}
