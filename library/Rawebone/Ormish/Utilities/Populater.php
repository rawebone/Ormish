<?php
namespace Rawebone\Ormish\Utilities;

class Populater
{
    protected $caster;
    protected $entityManager;

    public function __construct(Caster $caster, EntityManager $entityManager)
    {
        $this->caster = $caster;
        $this->entityManager = $entityManager;
    }

    /**
     * Converts a result set to an array of classes.
     * 
     * @param \PDOStatement $stmt
     * @param string $className
     * @param string $idField
     * @return array
     */
    public function populate(\PDOStatement $stmt, $className, $idField)
    {
        $records = array();
        $castMap = $this->entityManager->properties($className);

        while (($record = $stmt->fetch(\PDO::FETCH_ASSOC))) {
            $casted = $this->caster->toPhpTypes($castMap, $record);
            $records[] = $this->entityManager->create($className, $idField, $casted, false);
        }
        return $records;
    }
}
