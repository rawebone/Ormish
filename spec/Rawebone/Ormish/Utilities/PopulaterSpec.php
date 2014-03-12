<?php

namespace spec\Rawebone\Ormish\Utilities;

use Rawebone\Ormish\Entity;
use Rawebone\Ormish\Utilities\Caster;
use Rawebone\Ormish\Utilities\EntityManager;
use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class PopulaterSpec extends ObjectBehavior
{
    function let(Caster $caster, EntityManager $entityManager)
    {
        $this->beConstructedWith($caster, $entityManager);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Utilities\Populater');
    }

    /**
     * @param \PDOStatement $stmt
     * @param \Rawebone\Ormish\Utilities\Caster $caster
     * @param \Rawebone\Ormish\Utilities\EntityManager $entityManager
     */
    function it_should_populate(\PDOStatement $stmt, $caster, $entityManager, Entity $entity)
    {
        $id = 'id';
        $cls = 'Rawebone\Ormish\Entity';
        $stmt->fetch(\PDO::FETCH_ASSOC)->willReturn(array("a" => "b"), null);

        $caster->toPhpTypes(array("a" => "string"), array("a" => "b"))
               ->willReturn(array("a" => "b"));

        $entityManager->properties($cls)->willReturn(array("a" => "string"));
        $entityManager->create($cls, $id, array("a" => "b"))->willReturn(array($entity));

        $this->populate($stmt, $cls, $id)->shouldHaveCount(1);
    }
}
