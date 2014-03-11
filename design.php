<?php

/**
 * The goal is to keep the schema as close to the entity as possible,
 * As such information about the table should reside on the object
 * but not via fields or members but by metadata- the information
 * is not relevant to the object itself but to the system.
 *
 * We will convert this information into a Table object behind the
 * scenes so overall the implementation is not much different.
 *
 * @table data_table
 * @primaryKey id
 * @softDelete
 * @readOnly
 */
class MyEntity extends \Rawebone\Ormish\Entity
{
}


/**
 * As the Entity now contains the information about the schema,
 * we need to actually register it with the Database (as opposed
 * to registering a Table object).
 */

$db = new \Rawebone\Ormish\Database();
$db->registerEntity('MyEntity');

/**
 * Indeed, because we are using the Entity now our idea of
 * access via the database object is different too.
 */

$db->get('MyEntity')->find(1);


/**
 * This is nice, but also it would be useful if we could use
 * the pure OOP API in conjunction with a globals API:
 */

Rawebone\Ormish\Entity::globalsDatabase($db);

MyEntity::find(1); // translates to $db->get('MyEntity')->find(1);

/**
 * This gives us parity with other systems while only really
 * acting as a very small and light proxy.
 */


/**
 * The other goal of 0.3.0 is to provide type casting to and from
 * the database. For example, ID below should be cast to an integer
 * value as it is applied to the object; in addition times should
 * be converted in the same way so that they are actually DateTime
 * objects.
 *
 * This allows us to use the standard API tags (so that IDE's can
 * provide type hints).
 *
 * @property int $id
 * @property boolean $bool
 * @property \DateTime $created
 */
class MyOtherEntity extends \Rawebone\Ormish\Entity
{
}

/**
 * The last goal for 0.3.0 is relationship handling. We want to
 * expose a simple API which converts to standard queries behind
 * the scenes. The API will apply to the Entity object directly
 * and allows us to cache the results in the object itself.
 *
 * This does increase complexity in the Entity object slightly
 * but this can be refactored out in later versions.
 *
 * @table last_entity
 */
class MyLastEntity extends \Rawebone\Ormish\Entity
{
    public function others()
    {
        return $this->hasOne('MyOtherEntity');

        // Which equates to:

        return $this->getDatabase('MyOtherEntity')->findOneWhere('last_entity_id = ?', $this->id);
    }

    public function entities()
    {
        return $this->hasMany('MyEntity');

        // Which equates to:

        return $this->getDatabase('MyEntity')->findWhere('last_entity_id = ?', $this->id);
    }
}
