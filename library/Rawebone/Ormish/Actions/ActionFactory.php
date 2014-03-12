<?php

namespace Rawebone\Ormish\Actions;

use Rawebone\Ormish\Table;
use Rawebone\Ormish\Database;
use Rawebone\Ormish\GatewayInterface;
use Rawebone\Ormish\Executor;
use Rawebone\Ormish\SqlGeneratorInterface;
use Rawebone\Ormish\Utilities\Caster;
use Rawebone\Ormish\Utilities\Populater;
use Rawebone\Ormish\Utilities\ObjectCreator;
use Rawebone\Ormish\Utilities\EntityManager;

/**
 * Handles creating instance of Action objects, this is more convenient for
 * working with actions in the Gateway.
 */
class ActionFactory
{
    protected $entityManager;
    protected $executor;
    protected $generator;
    protected $objectCreator;
    protected $populater;
    protected $caster;

    public function __construct(EntityManager $em, Executor $ex, Populater $pop,
        SqlGeneratorInterface $gen, ObjectCreator $oc, Caster $caster)
    {
        $this->entityManager = $em;
        $this->executor = $ex;
        $this->generator = $gen;
        $this->populater = $pop;
        $this->objectCreator = $oc;
        $this->caster = $caster;
    }

    public function create($name, Database $db, Table $tbl, GatewayInterface $gw)
    {
        return $this->objectCreator->create($name, array(
                $db,
                $gw,
                $this->entityManager,
                $tbl,
                $this->executor,
                $this->populater,
                $this->generator,
                $this->caster
            ));
    }
}
