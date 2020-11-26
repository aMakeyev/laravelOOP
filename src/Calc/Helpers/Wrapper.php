<?php namespace Calc\Helpers;

class Wrapper {

    protected static $id = 0;
    public $i = 0;
    protected $object;

    function __construct($subject)
    {
        $this->i = ++static::$id;
        $this->object = $subject;
    }

    function __get($name)
    {
        return $this->object->{$name};
    }
}
