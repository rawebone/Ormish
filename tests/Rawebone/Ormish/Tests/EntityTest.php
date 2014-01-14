<?php
namespace Rawebone\Ormish\Tests;

use Rawebone\Ormish\Tests\Fixtures\BasicEntityFixture;

class EntityTest extends TestCase
{
    public function testOutputIsFiltered()
    {
        $ent = new BasicEntityFixture();
        
        $ent->complex_field = "Hello";
        $this->assertEquals("HELLO", $ent->complex_field);
    }
    
    public function testInputIsFiltered()
    {
        $ent = new BasicEntityFixture();
        
        $ent->number = 1;
        $this->assertEquals(2, $ent->number);
    }
    
    public function testNotFiltered()
    {
        $ent = new BasicEntityFixture();
        $ent->name = "Lopez";
        $this->assertEquals("Lopez", $ent->name);
    }
    
    public function testGetAll()
    {
        $ent = new BasicEntityFixture();
        $ent->name = "Lopez";
        $ent->number = 1;
        $ent->complex_field = "Hello";
        
        $all = array(
            "name" => "Lopez",
            "number" => 2,
            "complex_field" => "Hello"
        );
        
        // Returns all with output filtered
        $this->assertEquals($all, $ent->modelAll());
        
        // Returns all without the output being filtered
        $all["complex_field"] = "HELLO";
        $this->assertEquals($all, $ent->modelAll(false));
    }
    
    public function testChanges()
    {
        $ent = new BasicEntityFixture();
        $ent->name = "Lopez";
        $ent->number = 1;
        $ent->complex_field = "Hello";
        
        $changes = array(
            "name" => "Lopez",
            "number" => 2,
            "complex_field" => "Hello"
        );
        
        $this->assertEquals($changes, $ent->modelChanges());
        
        $ent->modelResetChanges();
        $this->assertEquals(array(), $ent->modelChanges());
    }
    
    public function testMultipleFieldChanges()
    {
        $ent = new BasicEntityFixture();
        $ent->name = "Lopez";
        
        $ent->modelResetChanges();
        
        $ent->name = "John";
        $ent->name = "Lopez";
        
        $this->assertEquals(array("name" => "Lopez"), $ent->modelChanges());
    }
    
    /**
     * @expectedException \Exception
     * @expectedExceptionMessage Called
     */
    public function testCanRelate()
    {
        $ent = new BasicEntityFixture();
        $ent->son();
    }
}
