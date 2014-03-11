<?php

namespace spec\Rawebone\Ormish\Utilities;

use PhpSpec\ObjectBehavior;
use Prophecy\Argument;

class CasterSpec extends ObjectBehavior
{
    function it_is_initializable()
    {
        $this->shouldHaveType('Rawebone\Ormish\Utilities\Caster');
    }

    function it_should_cast_to_php_types()
    {
        $map = array(
            "a" => "int",
            "b" => "bool",
            "c" => "float",
            "d" => "string"
        );

        $input = array(
            "a" => "1",
            "b" => "1",
            "c" => "0.0",
            "d" => "1"
        );

        $output = array(
            "a" => 1,
            "b" => true,
            "c" => 0.0,
            "d" => "1"
        );

        $this->toPhpTypes($map, $input)->shouldReturn($output);
    }

    function it_should_cast_date_strings_to_a_datetime()
    {
        $map = array("a" => "DateTime");
        $input = array("a" => "2014-01-01 00:00:00");

        $conv = $this->toPhpTypes($map, $input);
        $conv["a"]->shouldBeAnInstanceOf('DateTime');
    }

    function it_should_cast_to_string_representations()
    {
        $map = array(
            "a" => "int",
            "b" => "bool",
            "c" => "float",
            "d" => "string"
        );

        $input = array(
            "a" => 1,
            "b" => true,
            "c" => 0.0,
            "d" => "1"
        );

        $output = array(
            "a" => "1",
            "b" => "1",
            "c" => "0",
            "d" => "1"
        );

        $this->toDbTypes($map, $input)->shouldReturn($output);
    }

    function it_should_cast_datetimes_to_date_strings()
    {
        $map = array("a" => "DateTime");
        $input = array("a" => new \DateTime("2014-01-01 00:00:00"));

        $conv = $this->toDbTypes($map, $input);
        $conv["a"]->shouldBe('2014-01-01 00:00:00');
    }
}
