<?php namespace Calc\Validators;

class OrderValidator extends Validator
{
    public function __construct($data = null, $level = null)
    {
        parent::__construct($data, $level);

        static::$rules = require 'rules/order.php';
    }

    protected function registerValidatorRules()
    {
        parent::registerValidatorRules();
    }
}
