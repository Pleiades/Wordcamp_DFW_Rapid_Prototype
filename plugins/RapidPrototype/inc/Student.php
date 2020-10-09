<?php
namespace RapidPrototype;

// extends \Faker\FakerProviderPerson

class Student extends \Faker\Provider\Base
{
    protected static $formats = array(
        "{{lastName}} {{firstName}}",
        "{{firstName}} {{lastName}}"
    );
    protected static $firstName = array("Mark", "Adam");
    protected static $lastName = array("Clark", "Stewart");
    private static $prefix = array("Mr.", "Mrs.", "Ms.", "Miss", "Dr.");

    public static function prefix() {
        return static::randomElement(static::$prefix);
    }

    public static function firstName() {
        return static::prefix() . " " .
            static::randomElement(static::$firstName);
    }
}