<?php namespace Calc\Model;

class CalculationWrapper
{
    public $facade = 0;
    public $skeleton = 0;
    public $furniture = 0;
    public $construct_assembly = 0;
    public $total = 0;
    public $num = 0;
    public $pseudo_discount_percent_value = 0;
    public $pseudo_discount_meter = 0;

    /**
     * @var  Calculation $object
     */
    public $object;

    function __construct($object)
    {
        $this->object = $object;
        $this->construct_assembly = $object->additionalCoefficient->value
            * ($object->cost_construct + $object->cost_assembly);
    }

    function __get($name)
    {
        return $this->object->{$name};
    }

    public function totalWithInstallAndDelivery()
    {
        return
            $this->total +
            $this->object->delivery +
            $this->object->install +
            $this->climb_price +
            $this->pseudo_discount_percent_value;
    }
}
