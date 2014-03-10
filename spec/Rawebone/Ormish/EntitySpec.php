<?php

namespace spec\Rawebone\Ormish\Utilities;

use Rawebone\Ormish\Database;
use Rawebone\Ormish\GatewayInterface;
use Rawebone\Ormish\Utilities\Shadow;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntitySpec extends ObjectBehavior
{
    function let(Shadow $shadow, Database $db, GatewayInterface $gate)
    {
        // NB - trying to make this call anywhere else in the tests will fail...
        $this->beConstructedWith(array(
            "key" => "value"
        ));
        
        $this->letShadow($shadow);
        $this->letDatabase($db);
        $this->letGateway($gate);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Entity');
    }
    
    function it_should_get_a_value()
    {
        $this->key->shouldBe("value");
        $this->nonKey->shouldReturn(null);
    }
    
    function it_should_have_defaults()
    {
        $this->deleted->shouldReturn(0);
    }
    
    function it_should_set_a_value()
    {
        $this->key = "value";
        $this->key->shouldReturn("value");
    }
    
    function it_should_not_save_at_gateway($gate)
    {
        $gate->save(Argument::type('Rawebone\Ormish\Entity'))
             ->willReturn(false);
        
        $this->save()->shouldReturn(false);
    }
    
    function it_should_save($gate, $shadow)
    {
        $gate->save(Argument::type('Rawebone\Ormish\Entity'))
             ->willReturn(true);
        
        $shadow->update(Argument::type("array"))->shouldBeCalled();
        
        $this->save()->shouldReturn(true);
    }
    
    function it_should_not_delete_at_gateway($gate)
    {
        $gate->delete(Argument::type('Rawebone\Ormish\Entity'))
             ->willReturn(false);
        
        $this->delete()->shouldReturn(false);
    }
    
    function it_should_delete($gate, $shadow)
    {
        $gate->delete(Argument::type('Rawebone\Ormish\Entity'))
             ->willReturn(true);
        
        $shadow->update(Argument::type('array'))->shouldBeCalled();
        
        $this->delete()->shouldReturn(true);
        $this->deleted->shouldBe(1);
    }
    
    function it_should_return_all_values()
    {
        $this->all()->shouldReturn(array(
            "deleted" => 0,
            "key" => "value"
        ));
    }
    
    function it_should_return_changes($shadow)
    {
        $shadow->changes(array(
            "deleted" => 0,
            "key" => "value"
        ))->willReturn(array());
        
        $shadow->changes(array(
            "deleted" => 0,
            "key" => "new"
        ))->willReturn(array("key" => "new"));
        
        $this->changes()->shouldReturn(array());
        
        $this->key = "new";
        $this->changes()->shouldReturn(array(
            "key" => "new"
        ));
    }
    
    function it_should_have_change($shadow)
    {
        $shadow->changes(array(
            "deleted" => 0,
            "key" => "new"
        ))->willReturn(array("key" => "new"));
        
        $this->key = "new";
        $this->hasChanged()->shouldReturn(true);
    }
}
