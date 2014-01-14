<?php
namespace Rawebone\Ormish\Tests;

use Rawebone\Ormish\ModelInfo;

class ModelInfoTest extends TestCase
{
    public function testInfo()
    {
        $info = new ModelInfo("Howdy", "howdy");
        
        $this->assertEquals("Howdy", $info->model());
        $this->assertEquals("howdy", $info->table());
        $this->assertEquals(false, $info->noUpdates());
        $this->assertEquals(true, $info->softDelete());
        $this->assertEquals("id", $info->id());
    }
    
    public function testOverrides()
    {
        $info = new ModelInfo("Howdy", "howdy", "client_id", false, true);

        $this->assertEquals(true, $info->noUpdates());
        $this->assertEquals(false, $info->softDelete());
        $this->assertEquals("client_id", $info->id());
    }
}
