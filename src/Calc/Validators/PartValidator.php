<?php namespace Calc\Validators;

class PartValidator extends Validator
{
    public function __construct($data = null, $level = null)
    {
        parent::__construct($data, $level);

        static::$rules = require 'rules/part.php';
    }

    protected function registerValidatorRules()
    {
        parent::registerValidatorRules();

        \Validator::extend('article', function ($attribute, $value, $parameters)
        {
            if (preg_match('/^[-_.]+/', $value))
            {
                return false;
            }

            if (preg_match('/[-_.]+$/', $value))
            {
                return false;
            }

            return preg_match('/^[a-zA-Z0-9_.#-]+$/', trim($value));
        });
    }
}
