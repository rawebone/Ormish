<?php

namespace Rawebone\Ormish;

use Rawebone\Ormish\Utilities\DefaultsCreator;

/**
 * Provides a wrapper over the Entity management tools in the library, allowing
 * for easier access to data and caching.
 */
class EntityManager
{
    protected $mdm;
    protected $defaults;
    protected $objects;
    protected $cachedDefaults = array();
    
    public function __construct(DefaultsCreator $defaults, MetaDataManager $mdm,
        ObjectCreator $objects)
    {
        $this->defaults = $defaults;
        $this->mdm = $mdm;
        $this->objects = $objects;
    }

    /**
     * Returns the default field values for an Entity.
     * 
     * @param string $name
     * @return array
     */
    public function defaults($name)
    {
        if (!isset($this->cachedDefaults[$name])) {
            $this->cachedDefaults[$name] = $this->defaults->make($this->metadata($name));
        }
        
        return $this->cachedDefaults[$name];
    }

    /**
     * Returns the metadata for an Entity.
     * 
     * @param string $name
     * @return array
     */
    public function metadata($name)
    {
        // MetaDataManager already caches this information.
        return $this->mdm->metadata($name);
    }

    /**
     * Prepares an Entity for use in the application.
     * 
     * @param \Rawebone\Ormish\Entity $entity
     * @param \Rawebone\Ormish\GatewayInterface $gateway
     * @param \Rawebone\Ormish\Database $db
     * @param boolean $readOnly
     */
    public function prepare(Entity $entity, GatewayInterface $gateway, Database $db, $readOnly)
    {
        $shadow = (boolean)$readOnly ? new NullShadow() : new Shadow();
        $shadow->update($entity->all());
        
        $entity->letDatabase($db);
        $entity->letShadow($shadow);
        $entity->letGateway($gateway);
    }

    /**
     * Creates an instance of an Entity.
     * 
     * @param string $name
     * @param string $idField
     * @param array $initial
     * @return \Rawebone\Ormish\Entity
     */
    public function create($name, $idField, array $initial)
    {
        $data = array_merge($this->defaults($name), $initial);

        if (isset($data[$idField])) { // ID's should always be null on new instances
            $data[$idField] = null;
        }
        
        return $this->objects->create($name, $data);
    }
}
