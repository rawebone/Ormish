# Ormish

Ormish is an Object Relational Mapper with an emphasis on simplicity. It relies
on a certain amount of extension and adherence to database conventions. It's
has one, generic SQL connector at the moment for maximum compatibility.

The library aims to provide an active record pattern to database access.

*Note: This is a work in progress*

## Usage

The ORM is pretty simple to get to grips with:

```php
<?php

use Rawebone\Ormish\Table;
use Rawebone\Ormish\Factory;

// We use the factory to build a connection
$factory = new Factory("sqlite::memory:", "", "");
$orm = $factory->build();

// We then "Attach" tables to the container - these are used to provide the
// information we need to connect tables to classes and give options for
// the way SQL should be generated.
$orm->attach(new Table('My\Models\Entity', "data_table"));

// We can then access the database table via it's name, which returns a gateway
// object. This allows us to perform actions such as finding data on our table
// using the options from the Table object.
$gateway = $orm->data_table();
$gateway = $orm->get("data_table");

// We can then find a record
$model = $gateway->find(1);

// Data is accessed by properties
$model->name = "Barry";

// Relationships are accessed by methods
$model->sons(); // Sons[]

// We can save or delete the model directly 
$model->save();
$model->delete();

// Creates a new instance of the Model for our table
// An array of initial data can be passed in this call to populate the model.
$fresh = $gateway->create(array("name" => "john")); 

```

The markup for entities is also quite straight forward:


```php
namespace My\Models;

use Rawebone\Ormish\Entity as BaseEntity;

/**
 * Given that we do not have "real" methods and properties to provide an
 * interface, we can use the annotations (courtesy of ApiGen) for your IDE,
 * if it supports them:
 * 
 * @property int $id
 * @property string $name
 * @property string $complex_name;
 * @method \My\Models\Other sons()
 */
class Entity extends BaseEntity
{
    public function sons()
    {
        return $this->getDatabase()->findWhere("parent_id = ?", $this->id);
    }
}

```

There is a little more boilerplate required for this compared to other systems,
but the payoff is that you have a simple mechanism to keep everything in check.

## License

[MIT License](LICENSE). Go hog wild.
