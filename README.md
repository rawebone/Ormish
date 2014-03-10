# Ormish

Ormish is an Object Relational Mapper with an emphasis on simplicity. It relies
on a certain amount of extension and adherence to database conventions. It
has one, generic SQL connector at the moment for maximum compatibility.

The library aims to provide an active record pattern to database access,
with a basic syntax for general use and an ability to easily extend for your
own purposes. I am writing this primarily to learn the Domain and for
my own purposes, unless there is significant interest for other parties.

*Note: This is a work in progress*

## Installation

Installation is via [Composer](https://getcomposer.org):

```json
{
    "require": {
        "rawebone/ormish": "dev-master"
    }
}
```

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

## Contributing

### Namespaces

The root namespace `Rawebone\Ormish` is reserved for objects which are directly
visible to the end user (like Database, Table, Entity) for easy use of the API.

The `Rawbone\Ormish\Actions` namespace is for Actions that can be applied to a
`Gateway` object (for example: `$table->find(1)` would refer to a `Finder` action).
The goal is to create small objects containing the logic required for working
with the database in a clean and simple way.

The `Rawebone\Ormish\Utilities` namespace is for objects which are consumed
internally in the API and have no outward value to the users. The end user,
for example, does not need to see the `Shadow` or `NullShadow` objects to
use the API.

### Roadmap

#### For Version 0.2.0

* Complete implementation of `Actions` and remove this functionality from the
  `Gateway` object.
* Complete move of objects into the `Utilities` namespace which have no outward
  value to end users.
* Change to an Exception model for errors.

#### For Version 0.3.0

* Implement a type mapping system. This will be breaking as will it require
  additional annotations on the Entities.
* Implement annotations for Tables and deprecate attaching tables manually. This
  will help keep the Entities close to the database.
* Implement `Relationship` handling in a similar fashion to the `Actions`.

### For Version 0.4.0

* Implement caching (hopefully via a PSR) of results and possibly configuration
* Basic schema manipulation

### Testing

All tests are being run with [PhpSpec](http://phpspec.org).


## License

[MIT License](LICENSE). Go hog wild.
