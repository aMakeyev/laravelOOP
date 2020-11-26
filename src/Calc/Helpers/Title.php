<?php namespace Calc\Helpers;

class Title
{
    protected $value;

    function __construct($value)
    {
        $this->value = $value;
    }

    public function append($value = '', $delimiter = ' :: ')
    {
        $this->value .= $delimiter . $value;
    }

    public function prepend($value = '', $delimiter = ' :: ')
    {
        $this->value = $value . $delimiter . $this->value;
    }

    public function set($value = '')
    {
        $this->value = $value;
    }

    public function render()
    {
        return $this->value;
    }

    function __toString()
    {
        return $this->render();
    }
}
