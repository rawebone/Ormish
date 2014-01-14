<?php
namespace Rawebone\Ormish\Tests\Fixtures;

use Rawebone\Ormish\Entity;

/**
 * @property string $name
 * @property integer $number 
 * @property string $complex_field
 * @method void son()
 */
class BasicEntityFixture extends Entity
{
    protected $name;
    protected $number;
    protected $complex_field;
    
    protected function getComplexField()
    {
        return strtoupper($this->complex_field);
    }
    
    protected function setNumber($new)
    {
        return (int)$new + 1;
    }
    
    protected function relateSon()
    {
        throw new \Exception("Called");
    }
}
