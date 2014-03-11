<?php

namespace spec\Rawebone\Ormish\Actions;

use Prophecy\Argument;

class CreateSpec extends AbstractActionSpec
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Actions\Create');
    }

    /**
     * @param \Rawebone\Ormish\Utilities\EntityManager $em
     * @param \Rawebone\Ormish\Entity $ent
     * @param \Rawebone\Ormish\Table $tbl
     * @param \Rawebone\Ormish\Database $db
     */
    function it_should_return_a_new_entity_instance($em, $ent, $tbl, $db)
    {
        $entity = 'Rawebone\Ormish\Entity';
        $id = "id";
        $readOnly = false;

        $tbl->model()->willReturn($entity);
        $tbl->id()->willReturn($id);
        $tbl->readOnly()->willReturn($readOnly);

        $em->create($entity, $id, array())->willReturn($ent);
        $em->prepare($ent, Argument::type('Rawebone\Ormish\GatewayInterface'), $db, $readOnly)
            ->shouldBeCalled();

        $this->run(array())->shouldReturn($ent);
    }
}
