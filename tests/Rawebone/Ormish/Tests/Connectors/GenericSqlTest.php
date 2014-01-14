<?php
namespace Rawebone\Ormish\Tests\Connectors;

use Rawebone\Ormish\Tests\TestCase;
use Rawebone\Ormish\Connectors\GenericSql;

class GenericSqlTest extends TestCase
{
    protected $mockConn;
    protected $mockStmt;
    protected $tbl = "my_tbl";
    protected $id = "id";
    protected $is = 1;
    protected $data = array("field" => 1, "field_b" => "help");
    protected $where = "a = b";
    
    public function setUp()
    {
        parent::setUp();

        $this->mockConn = $this->prophet->prophesize("PDO");
        $this->mockStmt = $this->prophet->prophesize("PDOStatement");
    }
    
    public function testConnection()
    {
        $gsc = new GenericSql($this->mockConn->reveal());
        $this->assertSame($this->mockConn->reveal(), $gsc->connection());
    }
    
    public function testFind()
    {
        $stmt = $this->mockStmt;
        $stmt->execute(array($this->is))->shouldBeCalled();
        
        $conn = $this->mockConn;
        $conn->prepare("SELECT * FROM $this->tbl WHERE deleted = 0 AND $this->id = ?")
             ->willReturn($stmt->reveal())
             ->shouldBeCalled();
        
        
        $gsc = new GenericSql($conn->reveal());
        $res = $gsc->find($this->tbl, $this->id, $this->is);
        
        $this->assertSame($stmt->reveal(), $res);
    }
    
    public function testFindWhere()
    {
        $stmt = $this->mockStmt->reveal();
        $conn = $this->mockConn;
        $conn->query("SELECT * FROM $this->tbl WHERE deleted = 0 AND $this->where")
             ->willReturn($stmt)
             ->shouldBeCalled();
        
        
        $gsc = new GenericSql($conn->reveal());
        $res = $gsc->findWhere($this->tbl, $this->where);
        
        $this->assertSame($stmt, $res);
    }
    
    public function testRestore()
    {
        $stmt = $this->mockStmt;
        $stmt->execute(array($this->is))
             ->willReturn(true);
        
        $conn = $this->mockConn;
        $conn->prepare("UPDATE $this->tbl SET deleted = 0 WHERE $this->id = ?")
             ->willReturn($stmt->reveal())
             ->shouldBeCalled();
        
        
        $gsc = new GenericSql($conn->reveal());
        $res = $gsc->restore($this->tbl, $this->id, $this->is);
        
        $this->assertEquals(true, $res);
    }
    
    public function testInsert()
    {
        $stmt = $this->mockStmt;
        $stmt->execute(array(1, "help"))
             ->willReturn(true);
        
        $conn = $this->mockConn;
        $conn->prepare("INSERT INTO $this->tbl (field, field_b) VALUES(?, ?)")
             ->willReturn($stmt->reveal())
             ->shouldBeCalled();
        
        $conn->lastInsertId()->shouldBeCalled()->willReturn(true);
        
        $gsc = new GenericSql($conn->reveal());
        $this->assertEquals(true, $gsc->insert($this->tbl, $this->data));
    }
    
    public function testUpdate()
    {
        $stmt = $this->mockStmt;
        $stmt->execute(array(1, "help", $this->is))
             ->willReturn(true);
        
        $conn = $this->mockConn;
        $conn->prepare("UPDATE $this->tbl SET field = ?, field_b = ? WHERE $this->id = ?")
             ->willReturn($stmt->reveal())
             ->shouldBeCalled();
        
        $gsc = new GenericSql($conn->reveal());
        $this->assertEquals(true, $gsc->update($this->tbl, $this->data, $this->id, $this->is));
    }
    
    public function testUpdateWithPassedId()
    {
        // ID should be removed from the array
        $data = $this->data;
        $data[$this->id] = $this->is;
        
        $stmt = $this->mockStmt;
        $stmt->execute(array(1, "help", $this->is))
             ->willReturn(true);
        
        $conn = $this->mockConn;
        $conn->prepare("UPDATE $this->tbl SET field = ?, field_b = ? WHERE $this->id = ?")
             ->willReturn($stmt->reveal())
             ->shouldBeCalled();
        
        $gsc = new GenericSql($conn->reveal());
        $this->assertEquals(true, $gsc->update($this->tbl, $this->data, $this->id, $this->is));
    }
    
    public function testSoftDelete()
    {
        $stmt = $this->mockStmt;
        $stmt->execute(array($this->is))
             ->willReturn(true);
        
        $conn = $this->mockConn;
        $conn->prepare("UPDATE $this->tbl SET deleted = 1 WHERE $this->id = ?")
             ->willReturn($stmt->reveal())
             ->shouldBeCalled();
        
        $gsc = new GenericSql($conn->reveal());
        $this->assertEquals(true, $gsc->delete($this->tbl, $this->id, $this->is, true));
    }
    
    public function testHardDelete()
    {
        $stmt = $this->mockStmt;
        $stmt->execute(array($this->is))
             ->willReturn(true);
        
        $conn = $this->mockConn;
        $conn->prepare("DELETE FROM $this->tbl WHERE $this->id = ?")
             ->willReturn($stmt->reveal())
             ->shouldBeCalled();
        
        $gsc = new GenericSql($conn->reveal());
        $this->assertEquals(true, $gsc->delete($this->tbl, $this->id, $this->is, false));
    }
}
