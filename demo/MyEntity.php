<?php

/**
 * This is an Entity - an object which represents your data in the database,
 * it's relationship to other Entities and any business rules required at
 * the base data layer.
 *
 * The library takes care of handling access to data, leaving you to focus
 * on any relationships and business rules: You only have to provide it with
 * information so that it can work for you. That information is the parameters
 * you expect on the model (both for IDE hinting and use in creating defaults
 * and casting values) and information about the table the entity links to.
 *
 * Properties:
 *
 * @property int $id
 * @property bool $deleted
 * @property float $my_float
 * @property string $aString
 * @property \DateTime $created Ormish automatically converts data in the converted field to a DateTime and back
 *
 * Table:
 *
 * @primaryKey id
 * @table my_entities
 *
 * "softDelete" can be specified and means that 'deleted' will be set to 1 instead of a deletion being issued.
 * Specifying "readOnly" prevents deletions, inserts or updates being made on the table.
 */
class MyEntity extends \Rawebone\Ormish\Entity
{
}
