<?php namespace Calc\Model;

class SubjectWrapper
{
    public $i = 0;
    public $facade = 0;
    public $skeleton = 0;
    public $furniture = 0;
    public $total = 0;

    protected static $id = 0;
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
