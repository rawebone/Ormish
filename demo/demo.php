<?php

/////////////////////////////////////////
///
/// Ormish Demo
///
/////////////////////////////////////////

require_once __DIR__ . "/../vendor/autoload.php";
include_once __DIR__ . "/MyEntity.php";
include_once __DIR__ . "/EchoLog.php";

build_demo_database(($db = __DIR__ . "/demo.db"));


/// Initialisation
/// ==============
///
/// To make it easy to get up and off the ground with the library
/// a factory is included. It has more options than covered here
/// for allowing you to specify your own SQL Generators (for example)
/// but the long and short is that you pass through you your server
/// details and it gives you a configured Ormish database connection.

$factory  = new \Rawebone\Ormish\Factory("sqlite:$db", "", "");
$factory->setLogger(new EchoLog());
$database = $factory->build();


/// General Usage
/// =============
///
/// The basic pattern of usage is via an OOP API.

/// Firstly, we have to expose our Entities to the Database
$database->attach('MyEntity');

/// Now, we can create a new Entity through the API
$created = $database->get('MyEntity')->create(array("aString" => "blah"));
$created->my_float = 1.3; // Amend the values
$created->save();

/// Now we can use the finding methods built into the gateway
$found = $database->get('MyEntity')->find(1);
$found->delete();

/// We can also carry out work in a transaction so that if
/// one operation fails the rest of the actions rollback
(new \Rawebone\Ormish\Transaction($database->getExecutor(), function () use ($database) {

    $found = $database->get('MyEntity')->findWhere("aString = ?", "bingo");
    foreach ($found as $entity) {
        $entity->delete();
    }

}))->run();


/// Globals mode
/// ============
///
/// This pattern of usage is not recommend but supported
/// as it is sometimes more convenient and easier to
/// rationalise usage of the Entities.

/// We create the database in the same way, and pass this
/// through to the Entity
\Rawebone\Ormish\Entity::globalDatabase($database);

/// We can then access the gateway actions via static calls
$entity = MyEntity::find(1);


function build_demo_database($file)
{
    touch($file);
    $pdo = new \PDO("sqlite:$file", "", "", array(
        \PDO::ATTR_ERRMODE => \PDO::ERRMODE_EXCEPTION
    ));

    // Build the table
    $sql = "CREATE TABLE my_entities (" .
        "id INTEGER PRIMARY KEY AUTOINCREMENT, " .
        "deleted INTEGER, " .
        "my_float REAL, " .
        "aString TEXT, " .
        "created TEXT)";

    $pdo->exec($sql);
}

unlink($db);
