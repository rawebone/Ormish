<?php

namespace spec\Rawebone\Ormish\Utilities;

use Rawebone\Ormish\Entity;
use Rawebone\Ormish\Database;
use Rawebone\Ormish\GatewayInterface;
use Rawebone\Ormish\Utilities\DefaultsCreator;
use Rawebone\Ormish\Utilities\MetaDataManager;
use Rawebone\Ormish\Utilities\ObjectCreator;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class EntityManagerSpec extends ObjectBehavior
{
    function let(DefaultsCreator $defaults, MetaDataManager $mdm, ObjectCreator $objects)
    {
        $this->beConstructedWith($defaults, $mdm, $objects);
    }
    
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Utilities\EntityManager');
    }
    
    function it_should_return_metadata($mdm)
    {
        $mdm->properties("name")->willReturn(1);
        $this->properties("name")->shouldReturn(1);
    }
    
    function it_should_return_defaults($defaults, $mdm)
    {
        $mdm->properties("name")->willReturn(array());
        $defaults->make(array())->willReturn(1);
        
        $this->defaults("name")->shouldReturn(1);
    }
    
    function it_should_prepare_an_entity_with_a_null_shadow(Entity $ent, 
        GatewayInterface $gate, Database $db)
    {
        $readOnly = true;
        
        $ent->all()->willReturn(array())->shouldBeCalled();
        $ent->letDatabase($db)->shouldBeCalled();
        $ent->letGateway($gate)->shouldBeCalled();
        $ent->letShadow(Argument::type('Rawebone\Ormish\Utilities\NullShadow'))->shouldBeCalled();
        
        $this->prepare($ent, $gate, $db, $readOnly);
    }
    
    function it_should_prepare_an_entity_with_a_real_shadow(Entity $ent, 
        GatewayInterface $gate, Database $db)
    {
        $readOnly = false;
        
        $ent->all()->willReturn(array())->shouldBeCalled();
        $ent->letDatabase($db)->shouldBeCalled();
        $ent->letGateway($gate)->shouldBeCalled();
        $ent->letShadow(Argument::type('Rawebone\Ormish\Utilities\Shadow'))->shouldBeCalled();
        
        $this->prepare($ent, $gate, $db, $readOnly);
    }
    
    function it_should_create_an_entity($defaults, $mdm, $objects)
    {
        $name = "test";
        $idField = "id";
        $values  = array("a" => "b");
        
        $mdm->properties($name)->willReturn(array());
        $defaults->make(array())->willReturn(array(
            "id" => 0,
            "a" => "",
            "c" => ""
        ));
        
        $objects->create($name, array("id" => null, "a" => "b", "c" => ""), false)->willReturn(true);
        $this->create($name, $idField, $values)->shouldReturn(true);
    }

    function it_should_create_an_entity_without_nulling_the_id($defaults, $mdm, $objects)
    {
        $name = "test";
        $idField = "id";
        $values  = array("a" => "b");

        $mdm->properties($name)->willReturn(array());
        $defaults->make(array())->willReturn(array(
                "id" => 1,
                "a" => "",
                "c" => ""
            ));

        $objects->create($name, array("id" => 1, "a" => "b", "c" => ""), false)->willReturn(true);
        $this->create($name, $idField, $values, false)->shouldReturn(true);
    }

    function it_should_return_a_table_for_an_entity($mdm)
    {
        $mdm->table("Entity")->willReturn(array(
            "table" => "my_table",
            "primaryKey" => "id",
            "softDelete" => null,
            "readOnly"   => null
        ));

        $this->table("Entity")->shouldReturnAnInstanceOf('Rawebone\Ormish\Table');
    }
}
