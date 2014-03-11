<?php

namespace Rawebone\Ormish;

use Rawebone\Ormish\Actions\ActionFactory;

/**
 * This acts as a wrapper over the Actions, allowing us to conveniently package
 * the database handling without having to contain too much logic.
 */
class Gateway implements GatewayInterface
{
    protected $database;
    protected $table;
    protected $factory;
    
    public function __construct(Database $db, Table $tbl, ActionFactory $factory)
    {
        $this->database = $db;
        $this->table = $tbl;
        $this->factory = $factory;
    }

    public function create(array $initial = array())
    {
        return $this->getAction("Create")->run($initial);
    }

    public function delete(Entity $entity)
    {
        return $this->getAction("Deleter")->run($entity);
    }

    public function find($id)
    {
        return $this->getAction("Find")->run($id);
    }

    public function findOneWhere($conditions)
    {
        $callback = array($this->getAction("FindOneWhere"), "run");
        return call_user_func_array($callback, func_get_args());
    }

    public function findWhere($condition)
    {
        $callback = array($this->getAction("FindWhere"), "run");
        return call_user_func_array($callback, func_get_args());
    }

    public function save(Entity $entity)
    {
        return $this->getAction("Saver")->run($entity);
    }

    /**
     * Returns an Action object for use in by the Gateway.
     *
     * @param string $name
     * @return \Rawebone\Ormish\Actions\AbstractAction
     */
    protected function getAction($name)
    {
        return $this->factory->create(
            __NAMESPACE__ . "\\Actions\\$name",
            $this->database,
            $this->table,
            $this
        );
    }
}
