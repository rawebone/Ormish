<?php
namespace Rawebone\Ormish\Tests;

use Rawebone\Ormish\Container;
use Rawebone\Ormish\ModelInfo;
use Rawebone\Ormish\Connectors\GenericSql;

class ContainerTest extends TestCase
{
    /**
     * @expectedException \Rawebone\Ormish\InvalidTableException
     * @expectedExceptionMessage invalid_table
     */
    public function testCallingInvalidTableThrowsException()
    {
        $container = new Container(new GenericSql($this->conn));
        $container->invalid_table();
    }
    
    public function testAttachAndCall()
    {
        $container = new Container(new GenericSql($this->conn));
        $container->attach(new ModelInfo("Model", "models"));
        
        $this->assertInstanceOf("Rawebone\\Ormish\\GatewayInterface", $container->models());
    }
}
