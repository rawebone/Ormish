# Ormish

Ormish is an Object Relational Mapper with an emphasis on simplicity. It relies
on a certain amount of extension and adherence to database conventions. It's
has one, generic SQL connector at the moment for maximum compatibility.

*Note: This is a work in progress*

## Usage

The ORM is pretty simple to get to grips with:

```php
<?php

use Rawebone\Ormish\Container;
use Rawebone\Ormish\ModelInfo;
use Rawebone\Ormish\Connectors\GenericSql;

$orm = new Container(new GenericSql(new PDO("sqlite::memory:")));

// "Attach" tables to the container

$orm->attach(new ModelInfo("\\My\\Entities\\Entity", "data_table"));

$model = $orm->data_table()->find(1);
$model->name = "Barry";
$model->save();
$model->delete();

```


